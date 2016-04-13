<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Modules.php
     * @created   2016-04-13 22:15
     */

    namespace Library\IRC;

    /**
     * Load and control the modules
     *
     * Class Modules
     * @package Library\IRC
     */
    class Modules
    {

        /**
         * Holds the core bot class
         * @var Bot
         */
        private $bot;

        /**
         * A list of module objects
         * @var array
         */
        private $loadedModules = array();

        /**
         * Construct module controller class
         *
         * @param Bot $bot
         */
        public function __construct(Bot $bot)
        {
            $this->bot = $bot;
            $this->loadModules();
        }

        /**
         * Load modules as per the config
         */
        private function loadModules()
        {
            $modules = $this->bot->getConfig('modules');

            foreach ($modules as $name => $settings) {
                $modClass              = '\Library\Modules\\' . $name . '\\' . $name;
                $this->loadedModules[] = new $modClass($this->bot);
            }
        }


        /**
         * Receive event and send through to all modules
         *
         * @param $event
         * @param $details
         */
        public function sendEvents($event, $details)
        {
            $event = ucfirst(strtolower($event));
            foreach ($this->loadedModules as $m) {
                if (method_exists($m, $event)) {
                    $m->$event($details);
                }
            }
        }
    }