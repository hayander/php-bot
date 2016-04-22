<?php

    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Command.php
     * @created   2016-04-22 10:40
     */

    namespace Library\IRC;

    use Library\Collections\IRC;
    use Library\Base\Module;

    /**
     * Holds registered command details
     * Class Command
     * @package Library\IRC
     */
    class Command
    {

        /**
         * Holds the module which the command belongs to
         * @var \Library\Base\Module
         */
        private $module;

        /**
         * Holds the method which will perform the command
         * @var
         */
        private $method;

        /**
         * Required level for the command (String or 2 indices array of strings)
         * @var
         */
        private $level;


        /**
         * Construct Commands
         *
         * @param Module $module
         * @param        $method
         * @param        $level
         */
        public function __construct(Module $module, $method, $level)
        {
            $this->module = $module;
            $this->method  = $method;
            $this->level   = $level;

        }

        /**
         * Run the command using registered method
         *
         * @param $details
         */
        public function runCommand($details)
        {
            print_r($details);

            // Does the user have required user level
            if (IRC::hasLevel($details['address']['level'], $this->level)) {
                $method = $this->method;
                $this->module->$method($details['address'], $details['source'], $details);
            } else {
                $this->module->ircNotice($details['address']['nick'], strtoupper($details['command']) . ': Permission Denied');
            }

        }

    }