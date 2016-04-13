<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Say.php
     * @created   2016-04-13 22:18
     */

    namespace Library\Modules;

    use Library\Base\Module;

    class Startup extends Module
    {

        public function onConnect()
        {
            echo 'We just connected!!!' . PHP_EOL;
        }

    }