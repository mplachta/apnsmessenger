<?php

/**
 * ApnsMessenger
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

use Stp\ApnsMessenger\Exception\FileNotExistsException;
use Stp\ApnsMessenger\Exception\FileNotReadableException;
use Stp\ApnsMessenger\Exception\InvalidUriException;
use Stp\ApnsMessenger\Entity\Message as StpMessage;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Response\Message as Response;
use ZendService\Apple\Apns\Message;

/**
 * Service ApnsMessenger
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */
class ApnsMessengerService implements MessengerInterface
{
    protected $uri = Client::PRODUCTION_URI;
    protected $certificateFile;
    protected $passPhrase;

    protected $client;

    /**
     * APNS response object
     * @var Response
     */
    protected $response;

    /**
     * Sends message to iOS device identified by token.
     *
     * @param string     $deviceToken iOS device token
     * @param StpMessage $stpMessage  Message object with body, sound and badge
     *
     * @return int
     *
     * @throws FileNotExistsException
     * @throws FileNotReadableException
     */
    public function sendMessage($deviceToken, StpMessage $stpMessage)
    {
        $this->setCertificateFile($this->certificateFile);

        $this->client = new Client();
        $this->client->open(
            $this->uri,
            $this->certificateFile,
            $this->passPhrase
        );

        $alert = new Message\Alert();
        $alert->setBody($stpMessage->getBody());

        $message = new Message();
        $message->setId($stpMessage->getIdentifier());
        $message->setBadge($stpMessage->getBadge());
        $message->setSound($stpMessage->getSound());
        $message->setToken($deviceToken);
        $message->setAlert($alert);


        $this->response = $this->client->send($message);

        $this->client->close();

        return $this->response->getCode();
    }

    /**
     * Sets URI type (production, sandbox)
     *
     * @param int $uri URI type
     *
     * @return void
     *
     * @throws InvalidUriException
     */
    public function setUri($uri)
    {
        if (!in_array($uri, [Client::PRODUCTION_URI, Client::SANDBOX_URI])) {
            throw new InvalidUriException;
        }

        $this->uri = $uri;
    }

    /**
     * Sets certificate file path
     *
     * @param mixed $certificateFile Certificate file path
     *
     * @return void
     *
     * @throws FileNotExistsException
     * @throws FileNotReadableException
     */
    public function setCertificateFile($certificateFile)
    {
        if (!file_exists($certificateFile)) {
            throw new FileNotExistsException;
        }

        if (!is_readable($certificateFile)) {
            throw new FileNotReadableException;
        }

        $this->certificateFile = $certificateFile;
    }

    /**
     * Sets pass phrase used to decode certificate file
     *
     * @param mixed $passPhrase Pass phrase string
     *
     * @return void
     */
    public function setPassPhrase($passPhrase)
    {
        $this->passPhrase = $passPhrase;
    }
}
