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
        public $modules;

        /**
         * Holds the bot config
         * @var array
         */
        private $config;

        /**
         * Hold the channel list
         * @var
         */
        private $channels;


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

            // Send init event to the Event Handler
            $commandEvent = 'onInit';
            if (method_exists($this->event, $commandEvent)) {
                $this->event->$commandEvent();
            }
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
                    $rawNumeric   = $commandArgs[0];
                    $commandArgs  = array_splice($commandArgs, 1);

                    // Create events for known numeric
                    switch ($rawNumeric) {
                        case 422:
                        case 376:
                            $commandEvent = 'Connect';
                            $commandArgs  = null;
                            break;
                        default:
                            $commandArgs  = array(
                                'numeric'   => $rawNumeric,
                                'arguments' => $commandArgs,
                            );
                    }
                }

            } else {
                // Else, message related to a user

                $addressSplit = explode('!', substr($splitData[0], 1));
                $hostSplit    = explode('@', $addressSplit[1]);

                // Split up the address of the user
                $address = array(
                    'full'  => $addressSplit[0] . '!' . $addressSplit[1],
                    'nick'  => $addressSplit[0],
                    'ident' => $hostSplit[0],
                    'host'  => $hostSplit[1],
                );

                $command = ucfirst(strtolower($splitData[1]));

                $commandEvent = $command;
                $commandArgs  = array(
                    'address'   => $address,
                    'arguments' => array_splice($splitData, 2),
                );
            }
            // Send to the Event Handler
            $commandEvent = 'on' . $commandEvent;
            if (method_exists($this->event, $commandEvent)) {
                $this->event->$commandEvent($commandArgs);
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
                if (empty($this->config[$item])) {
                    return null;
                }
                return $this->config[$item];
            } else {
                return $this->config;
            }
        }

        /**
         * Add a user to a channel
         *
         * @param $channel
         * @param $address
         */
        public function addChannelUser($channel, $address)
        {
            $channelAssoc = strtolower($channel);

            // Create the channel if it doesn't exist
            if (!is_object($this->channels[$channelAssoc])) {
                $this->channels[$channelAssoc] = new Channel($channel);
            }

            // Suppress IDE inspection warning (Due to associative object creation)
            /** @noinspection PhpUndefinedMethodInspection */
            $this->channels[$channelAssoc]->addUser($address);
        }


        /**
         * Delete a user from a channel
         *
         * @param $channel
         * @param $address
         */
        public function delChannelUser($channel, $address)
        {
            $channelAssoc = strtolower($channel);

            // Destroy channel if the bot leaves it
            if ($address['nick'] == $this->getConfig('nick')) {
                unset($this->channels[$channelAssoc]);
            }
            if (is_object($this->channels[$channelAssoc])) {
                // Suppress IDE inspection warning (Due to associative object creation)
                /** @noinspection PhpUndefinedMethodInspection */
                $this->channels[$channelAssoc]->delUser($address['nick']);
            }
        }

        /**
         * Clear the channel user list.
         *
         * @param $channel
         */
        public function clearChannelUsers($channel)
        {
            $channelAssoc = strtolower($channel);

            if (is_object($this->channels[$channelAssoc])) {
                // Suppress IDE inspection warning (Due to associative object creation)
                /** @noinspection PhpUndefinedMethodInspection */
                $this->channels[$channelAssoc]->clearUsers();
            }
        }

        /**
         * Return the array of users of a channel
         *
         * @param $channel
         *
         * @return array
         */
        public function getChannelUsers($channel)
        {
            $channelAssoc = strtolower($channel);

            if (is_object($this->channels[$channelAssoc])) {
                // Suppress IDE inspection warning (Due to associative object creation)
                /** @noinspection PhpUndefinedMethodInspection */
                return $this->channels[$channelAssoc]->getUsers();
            }
            return array();
        }

        /**
         * Get details of a user on a specific channel (User level, etc)
         *
         * @param $channel
         * @param $nick
         *
         * @return array
         */
        public function getChannelUserDetails($channel, $nick)
        {
            $channelAssoc = strtolower($channel);

            if (is_object($this->channels[$channelAssoc])) {
                // Suppress IDE inspection warning (Due to associative object creation)
                /** @noinspection PhpUndefinedMethodInspection */
                return $this->channels[$channelAssoc]->getUserDetails($nick);
            }
            return array();
        }
    }
