<?php
    /**
     * Created by PhpStorm.
     * User: hayander
     * Date: 11/04/16
     * Time: 10:31 PM
     */

    namespace Library\IRC;

    class Core
    {

        private $connection;

        public function __construct()
        {
            $this->connection = new \library\irc\connection;
        }

        public function connect()
        {
            $this->connection->connect();
        }

    }

