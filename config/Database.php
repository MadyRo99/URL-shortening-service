<?php

/**
 * Class Database
 */
class Database {
    private $host = "localhost";
    private $db_name = "softlead_challenge";
    private $username = "root";
    private $password = "";

    private $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ];

    public $connection;

    /**
     * Create the database and the "links" table.
     */
    public function createDatabase()
    {
        try {
            $db = new PDO("mysql:host=" . $this->host, $this->username, $this->password);

            $createDatabaseSql = "CREATE DATABASE " . $this->db_name;
            $createLinksTableSql = "
                CREATE TABLE links (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    long_url varchar(2048) NOT NULL,
                    short_url varchar(10) NOT NULL UNIQUE,
                    expiration TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 10 MINUTE),
                    PRIMARY KEY (id),
                    INDEX (short_url) 
                );
            ";

            $createUsersTableSql = "
                CREATE TABLE users (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    ip_address VARBINARY(16) NOT NULL UNIQUE,
                    counter int(11) NOT NULL DEFAULT 1,
                    PRIMARY KEY (id),
                    INDEX (ip_address) 
                );
            ";

            $db->exec($createDatabaseSql) or die(print_r($db->errorInfo(), true));

            $connection = $this->getConnection();

            $connection->exec($createLinksTableSql);
            $connection->exec($createUsersTableSql);
        }
        catch (PDOException $e) {
            die("An error has occurred while creating the database: " . $e->getMessage());
        }

        header("Location: /");
    }

    /**
     * Get the database connection.
     *
     * @return PDO|null
     */
    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, $this->options);
        } catch (PDOException $exception) {
            echo "An error has occurred while connecting to the database: " . $exception->getMessage();
        }

        return $this->connection;
    }

    /**
     * Close the database connection.
     */
    public function closeConnection()
    {
        $this->connection = null;
    }
}