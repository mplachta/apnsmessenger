<?php

/**
 * MessengerInterface
 *
 * PHP version 5
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */

namespace Stp\ApnsMessenger\Service;

use Stp\ApnsMessenger\Entity\Message as StpMessage;

/**
 * Interface MessengerInterface
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */
interface MessengerInterface
{
    /**
     * Sends message to a device identified by deviceToken
     *
     * @param string     $deviceToken Device token
     * @param StpMessage $message     Message object with body, sound and badge
     *
     * @return int
     */
    public function sendMessage($deviceToken, StpMessage $message);
}
