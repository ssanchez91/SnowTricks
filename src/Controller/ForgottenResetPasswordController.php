<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Security\MailerSecurity;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ForgottenResetPasswordController
 * @package App\Controller
 */
class ForgottenResetPasswordController extends AbstractController
{
    /**
     * @Route("/forgotten/reset/password", name="forgotten_reset_password")
     */
    public function forgottenResetPassword(Request $request, UserService $userService, UserRepository $userRepository, MailerSecurity $mailerSecurity): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if(!$user)
            {
                $this->addFlash('danger', 'This email is not valid.');
                return $this->redirectToRoute('forgotten_reset_password');
            }

            $url = $userService->getUrlToEmail('reset_password', $userService->addToken($user));

            //send email activation
            $mailerSecurity->sendResetPasswordLink($user, $url);
            $this->addFlash('info', 'Check your email and click on the link to activate your account.');
        }

        return $this->render('forgotten_reset_password/request.html.twig', ['requestForm' => $form->createView()]);
    }

    /**
     * @Route("/reset/password", name="reset_password")
     */
    public function resetPassword(Request $request, UserRepository $userRepository, UserService $userService): Response
    {
        $username = $request->get('username');
        $user = $userRepository->findOneBy(array('username'=> $username));

        if (!$user || null === $username)
        {
            $this->addFlash('warning', 'Token Invalid, try again to reset your password account.');
            return $this->redirectToRoute('forgotten_reset_password');
        }

        if($userService->checkToken($user, $request->get('token')) === true)
        {
            $form = $this->createForm(ChangePasswordFormType::class);
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();

            if ($form->isSubmitted() && $form->isValid())
            {
                $user->setPassword($userService->createPassword($user, $form->get('plainPassword')->getData()));
                $user->setToken($userService->createToken($user));
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Your password has been changed successfully !');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('forgotten_reset_password/reset.html.twig', ['resetForm' => $form->createView()]);
        }

        $this->addFlash('warning', 'Token Invalid, try again to reset your password account.');
        return $this->redirectToRoute('forgotten_reset_password');
    }
}