<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Server.php
     * @created   2016-04-12 03:47
     */

    namespace Library\IRC;

    use Library\Socket\Connection;

    /**
     * Deals with the connection of the bot to the IRC server
     * Class Server
     * @package Library\IRC
     */
    class Server
    {

        /**
         * Holds the socket connection
         * @var \Library\Socket\Connection
         */
        private $connection;

        /**
         * Bots nickname
         * @var string
         */
        private $nickname = 'HYTEST';

        /**
         * Bots gecos (or 'real name' on IRC)
         * @var string
         */
        private $gecos = 'Hayander\'s Testing Bot';

        /**
         * Create the connection class
         */
        public function __construct()
        {
            $this->connection = new Connection;
        }

        /**
         * Connect the bot to the IRC server
         */
        public function connect()
        {
            if ($this->connection->connect()) {
                // Initialisation commands to identify the bot to the IRC server
                $this->sendData('USER ' . $this->nickname . ' * * :' . $this->gecos);
                $this->sendData('NICK ' . $this->nickname);
                // TODO: Check if properly connected. We must also initiate onConnect
            }
        }

        /**
         * Send data to IRC server
         * @param $data
         */
        public function sendData($data)
        {
            echo '>> ' . $data . PHP_EOL;
            $this->connection->sendData($data);
        }

        /**
         * Get data from the server
         * @return bool|string
         */
        public function getData()
        {
            return $this->connection->getData();
        }

        /**
         * Set the IRC server to connect to
         * @param $server
         */
        public function setServer($server)
        {
            $this->connection->setServer($server);
        }

    }