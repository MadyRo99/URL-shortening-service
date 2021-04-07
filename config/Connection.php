<?php

/**
 * Class Connection
 */
class Connection {
    protected $db;

    /**
     * Assign connection.
     * @param $database
     */
    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Destroy connection.
     */
    public function __destruct()
    {
        $this->db = null;
    }
}