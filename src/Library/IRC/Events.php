<?php
    /**
     * Created by PhpStorm.
     * User: hayander
     * Date: 12/04/16
     * Time: 11:06 PM
     */

    namespace Library\IRC;

    class Events
    {

        public function ping($arguments)
        {
            $this->sendData('PONG ' . $arguments);
        }

    }