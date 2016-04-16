<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Module.php
     * @created   2016-04-13 22:20
     */

    namespace Library\Base;

    use Library\IRC\Bot;

    /**
     * Base class for modules. Holds commonly used methods
     *
     * Class Module
     * @package Library\Base
     */
    class Module
    {

        /**
         * Hold the core Bot details
         * @var \Library\IRC\Bot
         */
        protected $bot;

        /**
         * Construct the base module class
         *
         * @param Bot $bot
         */
        public function __construct(Bot $bot)
        {
            $this->bot = $bot;
        }

        /**
         * Makes the bot join a channel
         *
         * @param        $channel
         * @param string $key
         */
        protected function ircJoin($channel, $key = '')
        {
            if ($key != '') {
                $channel .= ' ' . $key;
            }
            $this->bot->sendData('JOIN :' . $channel);
        }

        /**
         * Makes the bot send a message to source using text
         *
         * @param $source
         * @param $text
         */
        protected function ircPrivmsg($source, $text)
        {
            $text = explode("\n", $text);
            for ($i = 0; $i < count($text); $i++) {
                if (isset($text[$i]) && strlen(trim($text[$i])) > 0) {
                    $this->bot->sendData('PRIVMSG ' . $source . ' :' . $text[$i]);
                }
            }
        }

        /**
         * Makes the bot send a raw message to the server
         *
         * @param $text
         */
        protected function ircSendRaw($text)
        {
            $this->bot->sendData($text);
        }

        // TODO: Need to add IRC commands (Such as Notice, Describe, Part, Kick, Ban, Mode, Quit)

    }