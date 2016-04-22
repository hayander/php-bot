<?php
    /**
     * @package   PHP Bot
     * @author    hayander
     * @file      Events.php
     * @created   2016-04-12 23:06
     */

    namespace Library\IRC;

    /**
     * Performs functions on events received from the IRC Server
     * Class Events
     * @package Library\IRC
     */
    class Events
    {

        /**
         * @var \Library\IRC\Bot
         */
        private $bot;

        /**
         * Construct event class
         *
         * @param $bot
         */
        public function __construct(Bot $bot)
        {
            $this->bot = $bot;
        }

        public function onInit()
        {
            $this->bot->modules->sendEvents(__FUNCTION__, array());
        }

        /**
         * On Connect event
         */
        public function onConnect()
        {
            $this->bot->modules->sendEvents(__FUNCTION__, array());
        }

        /**
         * On Ping event
         *
         * @param $details
         */
        public function onPing($details)
        {
            // Keeps us connected to the server

            $methodDetails = array(
                'arguments' => implode(' ', $details),
            );

            $this->sendData('PONG ' . $methodDetails['arguments']);

            $this->bot->modules->sendEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for joins
         *
         * @param $details
         */
        public function onJoin($details)
        {
            $methodDetails = array(
                'address' => $details['address'],
                'channel' => substr($details['arguments'][0], 1),
            );

            $methodDetails['address']['level'] = '';

            $this->bot->addChannelUser($methodDetails['channel'], $methodDetails['address']);

            $this->bot->modules->sendEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for parts
         *
         * @param $details
         */
        public function onPart($details)
        {
            $methodDetails = array(
                'address' => $details['address'],
                'channel' => $details['arguments'][0],
            );

            if (isset($details[1])) {
                $methodDetails['partReason'] = implode(' ', array_splice($arguments, 1));
            }

            $this->bot->delChannelUser($methodDetails['channel'], $methodDetails['address']);

            $this->bot->modules->sendEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for Privmsg. Also handles commands
         *
         * @param $details
         */
        public function onPrivmsg($details)
        {
            $methodDetails = array(
                'address' => $details['address'],
                'source'  => $details['arguments'][0],
                'text'    => explode(' ', trim(substr(implode(' ', array_splice($details['arguments'], 1)), 1))),
            );

            $methodDetails['address']['level'] = $this->bot->getChannelUserDetails($methodDetails['source'], $methodDetails['address']['nick'])['level'];

            if ($methodDetails['source'] == $this->bot->getConfig('nick')) {
                $methodDetails['source'] = $methodDetails['address']['nick'];
            }

            if (strpos($methodDetails['text'][0], $this->bot->getConfig('commandPrefix')) == 0) {
                // This is a command
                $commandName = substr($methodDetails['text'][0], strlen($this->bot->getConfig('command_prefix')));

                $methodDetails['arguments'] = array_splice($methodDetails['text'], 1);

                $command = $this->bot->modules->isCommandRegistered($commandName);

                if ($command) {
                    print_r($methodDetails);
                    $this->bot->modules->runCommand($commandName, $methodDetails);
                }

            } else {
                // Else, not a command. Just text.
                $this->bot->modules->sendEvents(__METHOD__, $methodDetails);
            }
        }

        /**
         * Event handler for Mode.
         *
         * @param $details
         */
        public function onMode($details)
        {
            $methodDetails = array(
                'address'   => $details['address'],
                'source'    => $details['arguments'][0],
                'arguments' => explode(' ', trim(substr(implode(' ', array_splice($details['arguments'], 1)), 1))),
            );

            if (substr($methodDetails['source'], 0, 1) == '#') {
                // Mode change is in a channel. Reload user levels.
                $this->bot->clearChannelUsers($methodDetails['source']);
                $this->sendData('NAMES ' . $methodDetails['source']);
            }

            $this->bot->modules->sendEvents(__METHOD__, $methodDetails);
        }

        /**
         * Event handler for raw numeric messages
         *
         * @param $details
         */
        public function onRaw($details)
        {
            $numeric = $details['numeric'];
            switch ($numeric) {
                // Numeric 353 = /NAMES user list
                case 353:

                    $channel = strtolower($details['arguments'][2]);
                    $users   = array_splice($details['arguments'], 3);

                    // First user has a colon we have to remove
                    $users[0] = substr($users[0], 1);

                    // Add each user to the user list
                    foreach ($users as $u) {
                        $addressSplit = explode('!', $u);

                        // Split the nick and level
                        $nick  = str_replace(array('-', '+', '%', '&', '~'), '', $addressSplit[0]);
                        $level = str_replace($nick, '', $addressSplit[0]);

                        // Create empty address (In case server doesn't support UHNAMES)
                        $address = array(
                            'full'  => '',
                            'nick'  => $nick,
                            'ident' => '',
                            'host'  => '',
                            'level' => $level
                        );

                        // Populate the full address array if the server has sent it
                        if (isset($addressSplit[1])) {
                            $hostSplit        = explode('@', $addressSplit[1]);
                            $address['full']  = $nick . '!' . $addressSplit[1];
                            $address['ident'] = $hostSplit[0];
                            $address['host']  = $hostSplit[1];
                        }

                        $this->bot->addChannelUser($channel, $address);

                    }
                    break;
                // Numeric 005 = Lists supported options of server
                case 005:

                    $supports = array_splice($details['arguments'], 1);

                    // If server supports UHNAMES, tell them we do too (User hosts in /names response)
                    if (array_search('UHNAMES', $supports)) {
                        $this->sendData('PROTOCTL UHNAMES');
                    }
                    break;

            }

            $this->bot->modules->sendEvents(__METHOD__, $details);
        }

        /**
         * Send data to the server
         *
         * @param $data
         */
        private function sendData($data)
        {
            $this->bot->sendData($data);
        }
    }