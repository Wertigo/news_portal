<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EmailSender;

class IndexController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(EmailSender $emailSender)
    {
        $emailSender->sendRegistrationCompleteEmail('kdswto@gmail.com', 'Daniil Karnaukhov');
        return $this->render('index/index.html.twig');
    }
}