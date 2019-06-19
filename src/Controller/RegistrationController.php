<?php

namespace App\Controller;

use App\Factory\UserFactory;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use App\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserFactory                  $userFactory
     * @param EmailSender                  $emailSender
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserFactory $userFactory,
        EmailSender $emailSender
    ): Response {
        $user = $userFactory->createNew();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if ($emailSender->sendRegistrationCompleteEmail($user)) {
                $this->addFlash('success', 'Registration complete. We sending activation letter to your email');
            } else {
                $this->addFlash('warning', 'We can\'t send activation letter to your email, please contact to us.');
            }

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param string          $token
     * @param UserRepository  $userRepository
     * @param UserService     $userService
     * @param EmailSender     $emailSender
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function activateAccount(
        $token,
        UserRepository $userRepository,
        UserService $userService,
        EmailSender $emailSender,
        LoggerInterface $logger
    ): Response {
        $users = $userRepository->findBy(['activateToken' => $token]);

        if (count($users) > 1) {
            $logger->warning('Duplicate token string');
        }

        if (empty($users)) {
            throw $this->createNotFoundException('User with token - not found');
        }

        $user = array_pop($users);

        if ($user) {
            $userService->activateAccount($user);
            $emailSender->sendActivationCompleteEmail($user);
        }

        return $this->render('security/activation-complete.html.twig');
    }
}
