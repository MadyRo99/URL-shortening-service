<?php

/**
 * Class ShortenUrl
 */
class ShortenUrl extends Connection {

    /**
     * The current user IP Address.
     *
     * @var
     */
    protected $userIpAddress;
    /**
     * Stores how many short URLs have been created by the user with the given IP Address.
     *
     * @var
     */
    protected $userCount;

    /**
     * Inherit base class constructor.
     *
     * ShortenUrl constructor.
     * @param $database
     */
    public function __construct($database)
    {
        parent::__construct($database);
    }

    /**
     * Shorten the given URL.
     *
     * @param $long_url
     * @return array
     */
    public function shortenUrl($long_url) {
        $urlExists = $this->checkIfUrlExists($long_url);
        if ($urlExists) {
            return [
                'success' => true,
                'info'    => 'The URL has been successfully shortened!',
                'data'    => [
                    'short_url' => $urlExists['short_url']
                ]
            ];
        }

        $allowedToShorten = $this->checkIpAddress();

        if ($allowedToShorten) {
            $short_url = $this->shorten();

            $sql = "
                INSERT INTO links
                    (long_url, short_url)
                VALUES
                    (:long_url, :short_url)
            ";

            try {
                $query = $this->db->prepare($sql);
                $query->execute([
                    'long_url'  => $long_url,
                    'short_url' => $short_url
                ]);
            } catch(PDOException $e) {
                return [
                    'success' => false,
                    'info'    => 'An error has occurred while shorting the URL. Please try again later.',
                    'data'    => null
                ];
            }

            if ($this->userCount) {
                $sql = "
                    UPDATE users
                    SET counter = :counter
                    WHERE ip_address = :ip_address
                ";

                $query = $this->db->prepare($sql);
                $update = $query->execute([
                    'counter'    => $this->userCount + 1,
                    'ip_address' => $this->userIpAddress
                ]);

                if (!$update) {
                    return [
                        'success' => false,
                        'info'    => 'An error has occurred while shorting the URL. Please try again later.',
                        'data'    => null
                    ];
                }
            } else {
                $sql = "
                    INSERT INTO users
                        (ip_address, counter)
                    VALUES
                        (:ip_address, :counter)
                ";

                try {
                    $query = $this->db->prepare($sql);
                    $query->execute([
                        'ip_address' => $this->userIpAddress,
                        'counter'    => 1
                    ]);
                } catch(PDOException $e) {
                    return [
                        'success' => false,
                        'info'    => 'An error has occurred while shorting the URL. Please try again later.',
                        'data'    => null
                    ];
                }
            }

            return [
                'success' => true,
                'info'    => 'The URL has been successfully shortened!',
                'data'    => [
                    'short_url' => $short_url
                ]
            ];
        }

        return [
            'success' => false,
            'info'    => 'You are not allowed to short another URL. You have already shortened 5 URLs.',
            'data'    => null
        ];
    }

    /**
     * Check if the given URL has been shortened before
     * Returns null if not found
     * Returns the link if found
     *
     * @param $long_url
     * @return |null
     */
    protected function checkIfUrlExists($long_url) {
        $sql = "
            SELECT *
            FROM links
            WHERE long_url = :long_url
            AND NOW() <= expiration;
        ";

        $query = $this->db->prepare($sql);
        $query->execute([
            'long_url' => $long_url
        ]);

        $link = $query->fetch();

        if (empty($link)) {
            return null;
        }

        return $link;
    }

    /**
     * Check the user's IP address.
     * Returns true if user is allowed to short URL.
     * Returns false if user is not allowed to short URL.
     *
     * @return bool
     */
    protected function checkIpAddress() {
        $this->userIpAddress = inet_pton($this->getIpAddress());

        $sql = "
            SELECT *
            FROM users
            WHERE ip_address = :ip_address;
        ";

        $query = $this->db->prepare($sql);
        $query->execute([
            'ip_address' => $this->userIpAddress
        ]);
        $user = $query->fetch();

        if (empty($user)) {
            $this->userCount = 0;

            return true;
        } else {
            if ($user['counter'] < 5 ) {
                $this->userCount = $user['counter'];

                return true;
            }

            return false;
        }
    }

    /**
     * Get the IP address of the user whether IP is from share internet/proxy/remote address.
     *
     * @return mixed
     */
    protected function getIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Generate a random short URL and verify if it is unique in the database.
     *
     * @return string
     */
    protected function shorten() {
        do {
            $short_url = $this->generateUniqueUrl(10);
            if ($this->checkIfShortenedExists($short_url)) {
                continue;
            }

            return $short_url;
        } while (true);
    }

    /**
     * Algorithm for generating unique URL.
     *
     * @param $length
     * @return string
     */
    protected function generateUniqueUrl($length) {
        $charset = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';
        $count = strlen($charset);

        for ($i = 0; $i < $length; $i++) {
            $result .= $charset[mt_rand(0, $count - 1)];
        }

        return $result;
    }

    /**
     * Check if the generated short URL already exists in the database.
     *
     * @param $short_url
     * @return bool
     */
    protected function checkIfShortenedExists($short_url) {
        $sql = "
            SELECT *
            FROM links
            WHERE short_url = :short_url;
        ";

        $query = $this->db->prepare($sql);
        $query->execute([
            'short_url' => $short_url
        ]);
        $link = $query->fetch();

        if (empty($link)) {
            return false;
        }

        return true;
    }
}