<?php
    /**
     * Created by PhpStorm.
     * User: hayander
     * Date: 11/04/16
     * Time: 10:31 PMx
     */
    namespace Library\IRC;

    class Core extends Events
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
                if ($data = $this->server->getData()) {
                    echo '<< ' . $data . PHP_EOL;
                    $this->parseIRCData($data);
                }
            } while (true);
        }

        private function parseIRCData($data)
        {
            $splitData = explode(' ', $data);

            if (!strpos($splitData[0], '@')) {
                $command     = strtolower($splitData[0]);
                $commandArgs = implode(' ', array_splice($splitData, 1));
                if (method_exists($this, $command)) {
                    $this->$command($commandArgs);
                }

            } else {

                $addressSplit = explode('!', substr($splitData[0], 1));
                $hostSplit    = explode('@', $addressSplit[1]);

                $user = array(
                    'full'  => $addressSplit[0] . $addressSplit[1],
                    'nick'  => $addressSplit[0],
                    'ident' => $hostSplit[0],
                    'host'  => $hostSplit[1]
                );

                print_r($user);
            }

        }

        public function sendData($data)
        {
            $this->server->sendData($data);
        }
    }
