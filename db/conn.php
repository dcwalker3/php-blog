<?php
    class connection{
        private $host = 'localhost';
        private $user = 'root';
        private $password = 'Casci2318!';
        private $database = 'blog_site';
        private $dbConnection;

        public function __construct(){
            $this->dbConnection = new mysqli($this->host, $this->user, $this->password, $this->database);
        }

        public function getConnection(): mysqli
        {
            return $this->dbConnection;
        }
    }
?>