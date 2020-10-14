<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\MailerSecurity;
use App\Service\UserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    public function __construct()
    {

    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserService $userService, MailerSecurity $mailerSecurity): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //create user in database
            $user = $userService->createUser($user, $form->get('password')->getData());

            // generate a signed url to enable user
            $url = $this->generateUrl(
                'app_user_activation',
                [
                    'username' => $user->getUsername(),
                    'token' => $user->getToken(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            //send email activation
            $mailerSecurity->sendActivationLink($user, $url);

            $this->addFlash('info', 'Check your email and click on the link to activate your account.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activate/user", name="app_user_activation")
     */
    public function activateUser(Request $request, UserRepository $userRepository, UserService $userService): Response
    {
        $username = $request->get('username');
        $user = $userRepository->findBy(array('username'=> $username));

        if (!$user || null === $username)
        {
            $this->addFlash('warning', 'Error, this token is broken, try again to register.');
            return $this->redirectToRoute('app_register');
        }

        if($user[0]->getEnabled() === true )
        {
            $this->addFlash('warning', 'Your account has been already enabled !');
            return $this->redirectToRoute('app_login');
        }

        if($userService->checkToken($user[0], $request->get('token')) === true)
        {
            $this->addFlash('success', 'Your account has been successfully enabled !');
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('warning', 'Token Invalid, try again to register account.');
        return $this->redirectToRoute('app_register');
    }
}
