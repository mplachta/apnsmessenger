<?php

/**
 * Message
 *
 * PHP version 5
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */

namespace Stp\ApnsMessenger\Entity;

/**
 * Entity Message
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */
class Message
{
    protected $identifier;
    protected $badge;
    protected $sound;
    protected $body;

    /**
     * Returns message identifier
     * @return string
     */
    public function getIdentifier()
    {
        if (empty($this->identifier)) {
            $this->identifier = uniqid();
        }

        return $this->identifier;
    }

    /**
     * Sets message identifier
     *
     * @param string $identifier Message Identifier
     *
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Returns badge string
     * @return int
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Sets badge string
     *
     * @param int $badge Number supposed to be shown as Application Badge
     *
     * @return void
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
    }

    /**
     * Returns sound string
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Sets sound string
     *
     * @param string $sound Sound filename supposed to be played
     *  after notification is received
     *
     * @return void
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
    }

    /**
     * Returns body text
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets body text
     *
     * @param string $body Body text
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
