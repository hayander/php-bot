<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      index.php
     * @created   2016-04-11 21:12
     */

    use Library\IRC\Bot;

    try {

        require 'src/Loader.php';

        spl_autoload_register('\Library\Loader::load');

        if (file_exists(__DIR__ . '/Config/Config.php')) {

            // Load config, create bot and connect
            $config = include_once(__DIR__ . '/Config/Config.php');
            $bot = new Bot($config);
            //$bot->connect();
        }



    } catch (Exception $e) {
        echo('FATAL ERROR: ' . $e->getMessage() . PHP_EOL);
    }
