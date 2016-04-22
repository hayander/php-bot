<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Module.php
     * @created   2016-04-13 22:20
     */

    namespace Library\Base;

    use Library\IRC\Bot;
    use Library\IRC\Modules;

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
         * Holds the details of modules
         * @var \Library\IRC\Modules
         */
        protected $modules;

        /**
         * Construct the base module class
         *
         * @param Bot                  $bot
         * @param \Library\IRC\Modules $modules
         */
        public function __construct(Bot $bot, Modules $modules)
        {
            $this->bot     = $bot;
            $this->modules = $modules;

        }

        /**
         * Makes the bot join a channel
         *
         * @param        $channel
         * @param string $key
         */
        public function ircJoin($channel, $key = '')
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
        public function ircPrivmsg($source, $text)
        {
            $text = explode("\n", $text);
            for ($i = 0; $i < count($text); $i++) {
                if (isset($text[$i]) && strlen(trim($text[$i])) > 0) {
                    $this->bot->sendData('PRIVMSG ' . $source . ' :' . $text[$i]);
                }
            }
        }

        /**
         * Makes the bot send a notice to source using text
         *
         * @param $source
         * @param $text
         */
        public function ircNotice($source, $text)
        {
            $text = explode("\n", $text);
            for ($i = 0; $i < count($text); $i++) {
                if (isset($text[$i]) && strlen(trim($text[$i])) > 0) {
                    $this->bot->sendData('NOTICE ' . $source . ' :' . $text[$i]);
                }
            }
        }

        /**
         * Makes the bot send a raw message to the server
         *
         * @param $text
         */
        public function ircSendRaw($text)
        {
            $this->bot->sendData($text);
        }

        /**
         * Register command pass through
         *
         * @param        $name
         * @param        $function
         * @param string $level
         */
        public function registerCommand($name, $function, $level = '')
        {
            $this->modules->registerCommand($name, $function, $level);
        }

        // TODO: Need to add IRC commands (Such as Notice, Describe, Part, Kick, Ban, Mode, Quit)

    }