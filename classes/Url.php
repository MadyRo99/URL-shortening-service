<?php

/**
 * Class Url
 */
class Url extends Connection {

    /**
     * Inherit base class constructor.
     *
     * Url constructor.
     * @param $database
     */
    public function __construct($database)
    {
        parent::__construct($database);
    }

    /**
     * Retrieve the available active URLs from the database.
     *
     * @return array
     */
    public function getAvailableUrls() {
        $sql = "
            SELECT short_url, long_url, expiration
            FROM links
            WHERE NOW() <= expiration;
        ";

        $query = $this->db->prepare($sql);
        $query->execute();

        $links = $query->fetchAll();

        return [
            'success' => true,
            'info'    => 'URLs successfully retrieved.',
            'data'    => $links ? $links : null
        ];
    }
}