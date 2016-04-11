<?php
/**
 * Created by PhpStorm.
 * User: hayander
 * Date: 12/04/16
 * Time: 3:47 AM
 */

    namespace Library\IRC;

    class Server
    {

        private $connection;
        private $nickname = 'HYTEST';
        private $gecos = 'Hayander\'s Testing Bot';

        public function __construct()
        {
            $this->connection = new \Library\Socket\Connection;
        }

        public function connect()
        {
            if ( $this->connection->connect() ) {
                $this->sendRawCommand('USER ' . $this->nickname . ' * * :' . $this->gecos);
                $this->sendRawCommand('NICK ' . $this->nickname);
            }
        }

        public function sendRawCommand($data)
        {
            echo '>> ' . $data . PHP_EOL;
            $this->connection->sendData($data);
        }

        public function getIRCData()
        {
            return $this->connection->getData();
        }

        public function setServer($server)
        {
            $this->connection->setServer($server);
        }

    }