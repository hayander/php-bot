<?php
    /**
     * Created by PhpStorm.
     * User: hayander
     * Date: 11/04/16
     * Time: 10:31 PMx
     */

    namespace Library\IRC;

    class Core
    {

        private $server;

        public function __construct()
        {
            $this->server = new \Library\IRC\Server;
            $this->server->setServer('irc.hayander.com');
        }

        public function connect()
        {
            $this->server->connect();

            $this->mainLoop();
        }

        private function mainLoop()
        {
            do {
                $data = $this->server->getIRCData();
                echo '<< ' . $data . PHP_EOL;
            } while(true);
        }

    }

