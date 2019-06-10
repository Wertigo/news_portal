<?php

namespace App\Controller;

use App\Factory\UserFactory;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserFactory $userFactory
     * @param EmailSender $emailSender
     * @return Response
     * @throws \Exception
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserFactory $userFactory,
        EmailSender $emailSender
    ): Response
    {
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
            $emailSender->sendRegistrationCompleteEmail($user);

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function activateAccount(Request $request, $token, UserRepository $userRepository)
    {
        $user = $userRepository->findBy(['activateToken' => $token]);

        if ($user) {

        }
    }
}
