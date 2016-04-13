<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Modules.php
     * @created   2016-04-13 22:15
     */

    namespace Library\IRC;


    class Modules
    {

        private $bot;
        private $loadedModules;

        public function __construct(Bot $bot)
        {
            $this->bot = $bot;
            $this->loadModules();
        }

        public function loadModules()
        {
            $modules = $this->bot->getConfig('modules');

            foreach ( $modules as $name => $settings )
            {
                $modClass = '\Library\Modules\\' . $name;

                echo('Loading Module: ' . $modClass . ' - Settings: ' . print_r($settings, true) . PHP_EOL);


                $this->loadedModules[] = new $modClass;
            }
        }
    }