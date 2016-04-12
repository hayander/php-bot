<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      index.php
     * @created   2016-04-11 21:12
     */

    try {

        require 'src/Loader.php';

        spl_autoload_register('\Library\Loader::load');

        if (file_exists(__DIR__ . '/config.php')) {
            $config = include_once(__DIR__ . '/config.php');
        }

        // Create bot and connect
        $bot = new Library\IRC\Core;
        $bot->connect();

    } catch (Exception $e) {
        echo('FATAL ERROR: ' . $e->getMessage() . PHP_EOL);
    }
