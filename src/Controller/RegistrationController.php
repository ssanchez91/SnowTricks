<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\MailerSecurity;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * RegistrationController class
 */
class RegistrationController extends AbstractController
{
    /**
     * Register user
     * 
     * @Route("/register", name="app_register")
     *
     * @param Request $request
     * @param UserService $userService
     * @param MailerSecurity $mailerSecurity
     * @return Response
     */
    public function register(Request $request, UserService $userService, MailerSecurity $mailerSecurity): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //create user in database
            $user = $userService->createUser($user, $form->get('password')->getData(), $request);

            // generate a signed url to enable user
            $url = $userService->getUrlToEmail('app_user_activation', $user);

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
     * Enable user
     * 
     * @Route("/activate/user", name="app_user_activation")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserService $userService
     * @return Response
     */
    public function activateUser(Request $request, UserRepository $userRepository, UserService $userService): Response
    {
        $username = $request->get('username');
        $user = $userRepository->findOneBy(array('username'=> $username));

        if (!$user || null === $username)
        {
            $this->addFlash('warning', 'Error, this token is broken, try again to register.');
            return $this->redirectToRoute('app_register');
        }

        if($user->getEnabled() === true )
        {
            $this->addFlash('warning', 'Your account has been already enabled !');
            return $this->redirectToRoute('app_login');
        }

        if($userService->checkTokenToRegister($user, $request->get('token')) === true)
        {
            $this->addFlash('success', 'Your account has been successfully enabled !');
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('warning', 'Token Invalid, try again to register account.');
        return $this->redirectToRoute('app_register');
    }
}
