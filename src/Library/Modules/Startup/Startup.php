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
        public function commandTest($methodDetails)
        {
            $this->ircPrivmsg($methodDetails['source'], 'Hello, ' . $methodDetails['address']['nick'] . '! You said: ' . implode(' ', $methodDetails['arguments']));
        }

    }