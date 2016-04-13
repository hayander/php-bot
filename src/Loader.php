<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Loader.php
     * @created   2016-04-11 21:28
     */

    namespace Library;

    /**
     * Basic Auto Loader to load classes
     * Class Loader
     * @package Library
     */
    class Loader
    {

        /**
         * Loads the proper PHP file for the specified class
         *
         * @param $class
         *
         * @return mixed
         * @throws \Exception
         */
        public static function load($class)
        {
            $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($file)) {
                echo 'Loading Class: ' . $class . PHP_EOL;

                // Prevent IDE warning
                /** @noinspection PhpIncludeInspection */
                return require $file;
            }
            throw new \Exception('Unable to load class ' . $class);
        }
    }
