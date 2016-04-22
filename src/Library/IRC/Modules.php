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
         * Holds registered commands
         * @var \Library\IRC\Command
         */
        private $commands;

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
                $this->loadedModules[] = new $modClass($this->bot, $this);
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

        /**
         * Register a command with the bot
         *
         * @param        $object
         * @param        $name
         * @param string $level
         * @param string $method
         */
        public function registerCommand($object, $name, $level = '', $method = '')
        {
            echo 'Registering command ' . $name . ' from module ' . $object->moduleName . PHP_EOL;

            $name = strtolower($name);
            if (!isset($this->commands[$name])) {
                if (empty($method)) {
                    $method = 'command' . ucfirst($name);
                }
                if (method_exists($object, $method)) {
                    $this->commands[$name] = new Command($object, $method, $level);
                } else {
                    echo 'FAILED. Unable to register ' . $name . '. Method does not exist' . PHP_EOL;
                }
            }
        }

        /**
         * Determine if command is registered with the bot
         *
         * @param $name
         *
         * @return bool
         */
        public function isCommandRegistered($name)
        {
            $name = strtolower($name);
            if (isset($this->commands[$name])) {
                return $this->commands[$name];
            }
            return false;
        }

        /**
         * Run command pass through
         *
         * @param $name
         * @param $details
         */
        public function runCommand($name, $details)
        {
            $details['command'] = $name;
            // Suppress IDE inspection warning (Due to associative object creation)
            /** @noinspection PhpUndefinedMethodInspection */
            $this->commands[$name]->runCommand($details);

        }

        /**
         * Return command details
         *
         * @param string $command
         *
         * @return array
         */
        public function getCommandInfo($command = '')
        {
            if (empty($command)) {
                $commands = array();
                foreach ($this->commands as $cmd) {
                    $commands[] = (array) $cmd;
                }
                return $commands;
            } else {
                if (isset($this->commands[$command])) {
                    return (array) $this->commands[$command];
                }
            }
            return array();
        }
    }