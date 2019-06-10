<?php

namespace App\Service;

use App\Entity\User;
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
     * @param User $user
     * @return bool
     */
    public function sendRegistrationCompleteEmail(User $user): bool
    {
        try {
            $message = (new SwiftMessage('Hello Email'))
                ->setSubject('Registration complete')
                ->setFrom('web@aiti-pro.ru')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->twig->render('emails/registration-complete.html.twig', [
                        'name' => $user->getName(),
                        'activateToken' => $user->getActivateToken(),
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

    public function sendActivationCompleteEmail(User $user): bool
    {
        // TODO: implement
    }
}