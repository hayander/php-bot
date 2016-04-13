<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Connection.php
     * @created   2016-04-11 21:26
     */

    namespace Library\Socket;

    /**
     * Creates the socket used in the IRC connection
     * Class Connection
     * @package Library\Socket
     */
    class Connection
    {

        /**
         * Hold the socket resource
         * @var resource file pointer
         */
        private $socket;

        /**
         * Hold the server address
         * @var string
         */
        private $server = '';

        /**
         * Hold the server port
         * @var int
         */
        private $port = 6667;

        /**
         * Use SSL or not
         * @var bool
         */
        private $ssl = false;

        /**
         * Create the socket
         * @return bool
         */
        public function connect()
        {
            $this->socket = fsockopen(($this->ssl ? ('ssl://') : ('')) . $this->server, $this->port);

            if (!$this->isConnected()) {
                return false;
            }

            return true;
        }

        /**
         * Disconnect the socket
         * @return bool
         */
        public function disconnect()
        {
            if ($this->socket) {
                return fclose($this->socket);
            }

            return false;
        }

        /**
         * Determine if connected
         * @return bool
         */
        public function isConnected()
        {
            if (is_resource($this->socket)) {
                return true;
            }
            return false;
        }

        /**
         * Send data through the socket
         * @param $data
         */
        public function sendData($data)
        {
            fputs($this->socket, $data . "\r\n");
        }

        /**
         * Get data from the socket
         * @return bool|string
         */
        public function getData()
        {
            if (!feof($this->socket)) {
                $data = fgets($this->socket);
                if ($data) {
                    return $data;
                }
                return false;
            }
            return false;
        }

        /**
         * Set server to connect to
         * @param $server
         */
        public function setServer($server)
        {
            $this->server = (string) $server;
        }

        /**
         * Set port to connect to
         * @param $port
         */
        public function setPort($port)
        {
            $this->port = (int) $port;
        }

        /**
         * Use SSL or not
         * @param $ssl
         */
        public function setSSL($ssl)
        {
            $this->ssl = (bool) $ssl;
        }
    }

