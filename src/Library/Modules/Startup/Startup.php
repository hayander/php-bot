<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Say.php
     * @created   2016-04-13 22:18
     */

    /** @noinspection PhpUnusedParameterInspection */

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

        public function onInit()
        {
            $this->registerCommand($this, 'users', '~');
            $this->registerCommand($this, 'exec');
            $this->registerCommand($this, 'eval');
        }

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
         * @param $address
         * @param $source
         * @param $details
         *
         * @internal param $methodDetails
         */
        public function commandExec($address, $source, $details)
        {
            $this->ircSendRaw(implode(' ', $details['arguments']));
        }

        /**
         * !users to list users (Simply outputs the array of users)
         *
         * @param $address
         * @param $source
         * @param $details
         *
         * @internal param $methodDetails
         */
        public function commandUsers($address, $source, $details)
        {
            $this->ircPrivmsg($source, 'Thanks, ' . $address['nick']);
            $this->ircPrivmsg($source, print_r($this->bot->getChannelUsers($source), true));
        }

        /**
         * !eval to evaluate PHP functions
         *
         * @param $address
         * @param $source
         * @param $details
         */
        public function commandEval($address, $source, $details)
        {
            $args = $details['arguments'];
            $eval = implode(' ', $args) . ';';
            $this->ircPrivmsg($source, eval("return " . $eval));
            $this->ircPrivmsg($source, "Command performed");
        }
    }