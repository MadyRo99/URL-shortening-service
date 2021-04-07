<?php

/**
 * Class RedirectUrl
 */
class RedirectUrl extends Connection {

    /**
     * Inherit base class constructor.
     *
     * RedirectUrl constructor.
     * @param $database
     */
    public function __construct($database)
    {
        parent::__construct($database);
    }

    /**
     * Fetch for records in database based on the given shortUrl and return the available active URLs.
     *
     * @param $shortUrl
     * @return array
     */
    public function redirectToUrl($shortUrl) {
        $sql = "
            SELECT *
            FROM links
            WHERE short_url = :short_url;
        ";

        $query = $this->db->prepare($sql);
        $query->execute([
            'short_url' => $shortUrl
        ]);

        $link = $query->fetch();

        if (empty($link)) {
            return [
                'success' => false,
                'info'    => 'Invalid short URL.',
                'data'    => null
            ];
        }

        date_default_timezone_set( "Europe/Bucharest");
        $timeDiff = time() - strtotime($link['expiration']);
        if ($timeDiff > 0) {
            return [
                'success' => true,
                'info'    => 'The short URL you are trying to access has expired.',
                'data'    => null
            ];
        }

        return [
            'success' => true,
            'info'    => 'Redirecting...',
            'data'    => $link
        ];
    }
}