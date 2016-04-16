<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Say.php
     * @created   2016-04-13 22:18
     */

    namespace Library\Modules\Startup;

    use Library\Base\Module;

    /**
     * Startup module
     * Class Startup
     * @package Library\Modules\Startup
     */
    class Startup extends Module
    {

        /**
         * Module name
         * @var string
         */
        public $moduleName = 'Startup';

        /**
         * Module Author
         * @var string
         */
        public $moduleAuthor = 'hayander';

        /**
         * Startup on connect method
         */
        public function onConnect()
        {
            $this->ircJoin('#Bobman');
        }

        /**
         * Testing command
         *
         * @param $methodDetails
         */
        public function commandExec($methodDetails)
        {
            $this->ircSendRaw(implode(' ', $methodDetails['arguments']));
        }

        /**
         * !users to list users (Simply outputs the array of users)
         *
         * @param $methodDetails
         */
        public function commandUsers($methodDetails)
        {
            $this->ircPrivmsg($methodDetails['source'], print_r($this->bot->getChannelUsers($methodDetails['source']), true));
        }
    }