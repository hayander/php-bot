<?php
    /**
     * Created by PhpStorm.
     * User: hayander
     * Date: 11/04/16
     * Time: 9:26 PM
     */

    namespace Library\IRC;

    class Connection
    {

        private $socket;
        private $server = '';
        private $port = 6667;
        private $ssl = false;

        public function connect()
        {
            $this->socket = fsockopen( ( $this->ssl ? ( 'ssl://' ) : ( '' ) ) . $this->server, $this->port );

            if (!$this->isConnected()) {
                return false;
            }

            return true;
        }

        public function disconnect()
        {
            if ($this->socket) {
                return fclose( $this->socket );
            }

            return false;
        }

        public function isConnected()
        {
            if (is_resource( $this->socket )) {
                return true;
            }
            return false;
        }

        public function setServer($server)
        {
            $this->server = (string) $server;
        }

        public function setPort($port)
        {
            $this->port = (int) $port;
        }

        public function setSSL($ssl)
        {
            $this->ssl = (bool) $ssl;
        }
    }

