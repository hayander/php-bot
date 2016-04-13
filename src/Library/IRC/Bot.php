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
     * Class Bot
     * @package Library\IRC
     */
    class Bot
    {

        /**
         * Holds the server connection
         * @var \Library\IRC\Server
         */
        private $server;

        /**
         * Holds the event listener
         * @var Events
         */
        private $event;

        /**
         * Holds the module controller
         * @var Modules
         */
        private $modules;

        /**
         * Holds the bot config
         * @var array
         */
        private $config;

        /**
         * Constructs the IRC Bot
         */
        public function __construct($config = array())
        {
            if (!empty($config)) {
                $this->config = $config;
            }
            $this->server  = new Server;
            $this->event   = new Events($this);
            $this->modules = new Modules($this);

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
                $data = trim($this->server->getData());
                if ($data) {
                    echo '<< ' . $data . PHP_EOL;
                    $this->parseIRCData($data);
                }
            } while (true);
        }

        /**
         * Delegates actions for data received from the server.
         *
         * @param $data
         */
        private function parseIRCData($data)
        {
            $splitData = explode(' ', $data);

            if (!strpos($splitData[0], '@')) {
                // Message directly from server

                $commandArgs  = array_splice($splitData, 1);
                $commandEvent = ucfirst(strtolower($splitData[0]));

                // Determine raw numeric received.
                if (strpos($commandEvent, '.')) {
                    $commandEvent = 'Raw';
                    $rawNumeric = $commandArgs[0];
                    $commandArgs = implode(' ', array_splice($commandArgs, 1));

                    // Create events for known numeric
                    switch($rawNumeric)
                    {
                        case 422:
                        case 376:
                            $commandEvent = 'Connect';
                            $commandArgs = 0;
                            break;
                    }
                }

                // Send the server command to Event Handler
                $commandEvent = 'on' . $commandEvent;
                if (method_exists($this->event, $commandEvent)) {
                    $this->event->$commandEvent($commandArgs);
                }

            } else {
                // Else, message related to a user

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
         *
         * @param $data
         */
        public function sendData($data)
        {
            $this->server->sendData($data);
        }

        /**
         * Return the whole config or a particular config item
         *
         * @param $item
         *
         * @return array
         */
        public function getConfig($item)
        {
            if (!empty($item)) {
                return $this->config[$item];
            } else {
                return $this->config;
            }
        }

        /**
         * Send events through to modules
         *
         * @param $event
         * @param $details
         */
        public function sendModuleEvents($event, $details)
        {
            $this->modules->sendEvents($event, $details);
        }
    }
