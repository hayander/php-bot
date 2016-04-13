<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Events.php
     * @created   2016-04-12 23:06
     */

    namespace Library\IRC;

    /**
     * Performs functions on events received from the IRC Server
     * Class Events
     * @package Library\IRC
     */
    class Events
    {

        /**
         * @var \Library\IRC\Bot
         */
        private $bot;

        /**
         * Construct event class
         *
         * @param $bot
         */
        public function __construct(Bot $bot)
        {
            $this->bot = $bot;
        }

        /**
         * On Connect event
         */
        public function onConnect()
        {
            $this->bot->sendModuleEvents(__FUNCTION__, array());
        }

        /**
         * On Ping event
         *
         * @param $details
         */
        public function onPing($details)
        {
            // Keeps us connected to the server

            $methodDetails = array(
                'arguments' => implode(' ', $details),
            );

            $this->sendData('PONG ' . $methodDetails['arguments']);

            $this->bot->sendModuleEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for joins
         *
         * @param $details
         */
        public function onJoin($details)
        {
            $methodDetails = array(
                'channel' => $details['arguments'][0],
            );

            $this->bot->sendModuleEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for parts
         *
         * @param $details
         */
        public function onPart($details)
        {
            $methodDetails = array(
                'channel'    => $details['arguments'][0],
                'partReason' => implode(' ', array_splice($arguments, 1)),
            );

            $this->bot->sendModuleEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for Privmsg. Also handles commands
         *
         * @param $details
         */
        public function onPrivmsg($details)
        {
            $methodDetails = array(
                'address' => $details['address'],
                'source'  => $details['arguments'][0],
                'text'    => explode(' ', trim(substr(implode(' ', array_splice($details['arguments'], 1)), 1))),
            );

            if ($methodDetails['source'] == $this->bot->getConfig('nick')) {
                $methodDetails['source'] = $methodDetails['address']['nick'];
            }

            if (strpos($methodDetails['text'][0], $this->bot->getConfig('commandPrefix')) == 0) {
                // This is a command
                $command = substr($methodDetails['text'][0], strlen($this->bot->getConfig('command_prefix')));

                $methodDetails['arguments'] = array_splice($methodDetails['text'], 1);

                $this->bot->sendModuleEvents('command' . $command, $methodDetails);
            } else {
                $this->bot->sendModuleEvents(__METHOD__, $methodDetails);
            }
        }

        /**
         * Send data to the server
         *
         * @param $data
         */
        private
        function sendData($data)
        {
            $this->bot->sendData($data);
        }
    }