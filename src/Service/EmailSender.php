<?php

namespace App\Service;

use Swift_Mailer as SwiftMailer;
use Swift_Message as SwiftMessage;
use Twig\Environment;
use Psr\Log\LoggerInterface;

class EmailSender
{
    /**
     * @var SwiftMailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EmailSender constructor.
     * @param SwiftMailer $mailer
     */
    public function __construct(SwiftMailer $mailer, Environment $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @param $receiverEmail
     * @param $name
     * @return bool
     */
    public function sendRegistrationCompleteEmail($receiverEmail, $name): bool
    {
        try {
            $message = (new SwiftMessage('Hello Email'))
                ->setFrom('kdswto@gmail.com')
                ->setTo($receiverEmail)
                ->setBody(
                    $this->twig->render('emails/registration-complete.html.twig', [
                        'name' => $name
                    ]),
                    'text/html'
                )
            ;

            return (bool) $this->mailer->send($message);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [__METHOD__, __CLASS__]);

            return false;
        }
    }
}