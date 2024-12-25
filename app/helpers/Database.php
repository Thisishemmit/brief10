<?php
require_once 'errors.php';

class Database
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $conn = null;
    // PDO options to be passed to the PDO instance
    private $options = [
        // Throw a PDOException if an error occurs
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Return rows as an array indexed by column name
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Prevent from SQL injection
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public function __construct($username, $password, $dbname, $host = 'localhost')
    {
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->host = $host;
    }

    public function isConnected() {
        return $this->conn !== null;
    }

    public function connect() {
        if (!$this->isConnected()) {
            try {
                $dsn = "mysql:host=$this->host;dbname=$this->dbname";
                $this->conn = new PDO($dsn, $this->username, $this->password, $this->options);
                return true;
            } catch (PDOException $e) {
                show_error($e->getMessage());
                die();
            }
        }
    }

    public function disconnect() {
        $this->conn = null;
    }

    public function query($sql, $params = []) {
        if ($this->isConnected()) {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } else {
            return false;
        }
    }

    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
}
