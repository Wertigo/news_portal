<?php

namespace App\Service;

use App\Entity\User;
use Swift_Mailer as SwiftMailer;
use Swift_Message as SwiftMessage;
use Twig\Environment;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * EmailSender constructor.
     *
     * @param SwiftMailer           $mailer
     * @param Environment           $twig
     * @param LoggerInterface       $logger
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        SwiftMailer $mailer,
        Environment $twig,
        LoggerInterface $logger,
        ParameterBagInterface $parameterBag
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function sendRegistrationCompleteEmail(User $user): bool
    {
        if ($this->receiverIsEmpty($user)) {
            $this->logger->error("Can't send registration complete email without user email");

            return false;
        }

        return $this->sendEmail(
            'Registration complete',
            $user->getEmail(),
            'web@aiti-pro.ru',
            'emails/registration-complete.html.twig', [
                'name' => $user->getName(),
                'activateToken' => $user->getActivateToken(),
        ]);
    }

    /**
     * @param mixed $receiver
     * @return bool
     */
    private function receiverIsEmpty($receiver): bool
    {
        if ($receiver instanceof User) {
            return empty($receiver->getEmail());
        }

        return is_string($receiver) && empty($receiver);
    }

    /**
     * @param string $subject
     * @param string $receiver
     * @param string $sender
     * @param string $template
     * @param array  $params
     *
     * @return bool
     */
    private function sendEmail($subject, $receiver, $sender, $template, $params): bool
    {
        if ($this->receiverIsEmpty($receiver)) {
            $this->logger->error("Can't send email without user email");

            return false;
        }

        try {
            $message = (new SwiftMessage($subject))
                ->setFrom($sender)
                ->setTo($receiver)
                ->setBody($this->twig->render($template, $params), 'text/html')
            ;

            return (bool) $this->mailer->send($message);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [__METHOD__, __CLASS__]);

            return false;
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function sendActivationCompleteEmail(User $user): bool
    {
        if ($this->receiverIsEmpty($user)) {
            $this->logger->error("Can't send activation complete email without user email");

            return false;
        }

        return $this->sendEmail(
            'Activation complete',
            $user->getEmail(),
            'web@aiti-pro.ru',
            'emails/activation-complete.html.twig', [
                'name' => $user->getName(),
        ]);
    }
}
