<?php
    /**
     * Created by PhpStorm.
     * User: hayander
     * Date: 11/04/16
     * Time: 9:28 PM
     */
    class Loader
    {
        public static function load($class)
        {
            $file = __DIR__ . '/' . str_replace( '\\', '/', $class ) . '.php';
            if (file_exists( $file )) {
                echo 'Loading Class: ' . $class . PHP_EOL;
                return require $file;
            }
            throw new Exception( 'Unable to load class ' . $class );
        }
    }
