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
         * @param $arguments
         */
        public function onPing($arguments)
        {
            // Keeps us connected to the server
            $this->sendData('PONG ' . $arguments);

            $methodDetails = array(
                'arguments' => $arguments,
            );

            $this->bot->sendModuleEvents(__METHOD__, $methodDetails);
        }

        /**
         * Send data to the server
         *
         * @param $data
         */
        private function sendData($data)
        {
            $this->bot->sendData($data);
        }
    }