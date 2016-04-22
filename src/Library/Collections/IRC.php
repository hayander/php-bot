<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      IRC.php
     * @created   2016-04-22 17:52
     */

    namespace Library\Collections;

    /**
     * Collections of functions related to IRC
     * Class IRC
     * @package Library\Collections
     */
    class IRC
    {

        /**
         * Compare user level with required level
         *
         * @param $userLevel
         * @param $requiredLevel
         *
         * @return bool
         */
        public static function hasLevel($userLevel, $requiredLevel)
        {
            return self::convertLevel($userLevel) >= self::convertLevel($requiredLevel);
        }

        /**
         * Convert user level prefix into integer
         *
         * @param $level
         *
         * @return int|mixed
         */
        public static function convertLevel($level)
        {
            if (!in_array($level, array('-', '+', '%', '@', '&', '~'))) {
                return 0;
            }
            return str_replace(array('-', '+', '%', '@', '&', '~'), array(1, 3, 4, 5, 10, 9000), $level);
        }
    }