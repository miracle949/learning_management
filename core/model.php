<?php

class Model {
    protected $db;

    public function __construct(){
        $this->db = new mysqli(
            "localhost",
            "root",
            "",
            "ilearn_database"
        );

        if ($this->db->connect_error) {
            die("Database connection failed");
        }
    }

}
