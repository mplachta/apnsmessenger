<?php

/**
 * SendMessageCommand
 *
 * PHP version 5
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */

namespace Stp\ApnsMessenger\Command;

use Stp\ApnsMessenger\Entity\Message;
use Stp\ApnsMessenger\Service\MessengerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZendService\Apple\Apns\Response\Message as Response;

/**
 * Command SendMessageCommand
 *
 * @category ApnsMessenger
 * @package  StpApnsMessenger
 * @author   Michal Plachta <michal.plachta@schibsted.pl>
 * @license  http://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/mplachta/apnsmessenger
 */
class SendMessageCommand extends Command
{
    /**
     * MessengerInterface Service
     * @var MessengerInterface
     */
    protected $messenger;

    protected static $responseCodes = [
        Response::RESULT_PROCESSING_ERROR => 'Processing error, you may retry',
        Response::RESULT_MISSING_TOKEN => 'Missing device token',
        Response::RESULT_MISSING_TOPIC => 'Missing message id',
        Response::RESULT_MISSING_PAYLOAD => 'Missing payload',
        Response::RESULT_INVALID_TOKEN_SIZE => 'Invalid token size',
        Response::RESULT_INVALID_TOPIC_SIZE => 'Topic too long',
        Response::RESULT_INVALID_PAYLOAD_SIZE => 'Payload too long',
        Response::RESULT_INVALID_TOKEN => 'Invalid device token',
        Response::RESULT_UNKNOWN_ERROR => "Unknown error"
    ];

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('stp:message:send')
            ->setDescription('Sends a message to iOS device')
            ->addArgument(
                'deviceToken',
                InputArgument::REQUIRED,
                'APS device token'
            )
            ->addArgument(
                'message',
                InputArgument::REQUIRED,
                'A message to be sent'
            )
            ->addOption(
                'badge',
                'b',
                InputOption::VALUE_OPTIONAL,
                'Badge value'
            )
            ->addOption(
                'sound',
                's',
                InputOption::VALUE_OPTIONAL,
                'Sound file'
            );
    }

    /**
     * {@inheritdoc}
     *
     * @param InputInterface  $input  Input interface
     * @param OutputInterface $output Output interface
     *
     * @throws \Stp\ApnsMessenger\Exception\FileNotExistsException
     * @throws \Stp\ApnsMessenger\Exception\FileNotReadableException
     *
     * @return int Response code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Schibsted Tech Polska :: APNS Tester</info>');

        $deviceToken = $input->getArgument('deviceToken');

        $message = new Message();
        $message->setBadge($input->getOption('badge'));
        $message->setBody($input->getArgument('message'));
        $message->setSound($input->getOption('sound'));

        $responseCode = $this->messenger->sendMessage($deviceToken, $message);

        if (0 === $responseCode) {
            $output->writeln('<info>Message successfully sent</info>');
            return $responseCode;
        }

        $output->writeln('<error>Error while sending, with message:</error>');
        $output->writeln(
            '<error>'.self::$responseCodes[$responseCode].'</error>'
        );
        return $responseCode;
    }

    /**
     * Sets MessengerInterface Service
     *
     * @param MessengerInterface $messengerInterface MessengerInterface Service
     *
     * @return void
     */
    public function setMessenger(MessengerInterface $messengerInterface)
    {
        $this->messenger = $messengerInterface;
    }
}
