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
        private $bot;

        /**
         * Construct the base module class
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

        // TODO.

    }