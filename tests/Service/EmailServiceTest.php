<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\EmailSender;
use App\Factory\UserFactory;

class EmailServiceTest extends WebTestCase
{
    /**
     * @var EmailSender
     */
    private $emailSender;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
        $this->emailSender = self::$container->get(EmailSender::class);
    }

    /**
     * @throws \Exception
     */
    public function testSendRegistrationCompleteEmail()
    {
        $user = $this->getUser();

        // without email - service can't send email
        $this->assertFalse($this->emailSender->sendRegistrationCompleteEmail($user));

        $user->setEmail('test@test.com');
        $this->assertTrue($this->emailSender->sendRegistrationCompleteEmail($user));
    }

    /**
     * @return \App\Entity\User
     * @throws \Exception
     */
    private function getUser()
    {
        $factory = new UserFactory();

        return $factory->createNew();
    }

    /**
     * @throws \Exception
     */
    public function testSendActivationCompleteEmail()
    {
        $user = $this->getUser();

        // without email - service can't send email
        $this->assertFalse($this->emailSender->sendActivationCompleteEmail($user));

        $user->setEmail('test@test.com');
        $this->assertTrue($this->emailSender->sendActivationCompleteEmail($user));
    }
}