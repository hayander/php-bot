<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Core.php
     * @created   2016-04-11 22:31
     */

    namespace Library\IRC;

    /**
     * The core IRC bot class. The entry point of the bot.
     * Class Core
     * @package Library\IRC
     */
    class Core
    {

        /**
         * Holds the server connection
         * @var \Library\IRC\Server
         */
        private $server;

        /**
         * Constructs the IRC Bot
         */
        public function __construct()
        {
            $this->server = new \Library\IRC\Server;
            $this->event  = new \Library\IRC\Events($this);

            $this->server->setServer('irc.hayander.com');
        }

        /**
         * Connect the bot to the server. Start the main loop.
         */
        public function connect()
        {
            $this->server->connect();
            $this->mainLoop();
        }

        /**
         * The main loop of the bot. All control flows from here.
         */
        private function mainLoop()
        {
            do {
                $data = $this->server->getData();
                if ($data) {
                    echo '<< ' . $data . PHP_EOL;
                    $this->parseIRCData($data);
                }
            } while (true);
        }

        /**
         * Delegates actions for data received from the server.
         * @param $data
         */
        private function parseIRCData($data)
        {
            $splitData = explode(' ', $data);

            // Message directly from server
            if (!strpos($splitData[0], '@')) {
                $commandArgs   = implode(' ', array_splice($splitData, 1));
                $commandEvent = 'on' . ucfirst(strtolower($splitData[0]));

                // Send the server command to Event Handler
                if (method_exists($this->event, $commandEvent)) {
                    $this->event->$commandEvent($commandArgs);
                }

            // Else, message related to a user
            } else {

                $addressSplit = explode('!', substr($splitData[0], 1));
                $hostSplit    = explode('@', $addressSplit[1]);

                $user = array(
                    'full'  => $addressSplit[0] . $addressSplit[1],
                    'nick'  => $addressSplit[0],
                    'ident' => $hostSplit[0],
                    'host'  => $hostSplit[1]
                );
            }

        }

        /**
         * Send data to the server
         * @param $data
         */
        public function sendData($data)
        {
            $this->server->sendData($data);
        }
    }
