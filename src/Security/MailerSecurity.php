<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 14/10/2020
 * Time: 14:25
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerSecurity
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var ContainerBagInterface
     */
    private $containerBagInterface;

    /**
     * @param MailerInterface $mailer
     * @param ContainerBagInterface $containerBagInterface
     */
    public function __construct(MailerInterface $mailer, ContainerBagInterface $containerBagInterface)
    {
        $this->mailer = $mailer;
        $this->containerBagInterface = $containerBagInterface;
    }

    public function sendActivationLink(User $user, string $url)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->containerBagInterface->get('from_address'), 'WebMaster SnowTricks'))
            ->to(new Address($user->getEmail()))
            ->subject('Activation de votre compte SnowTricks')
            ->htmlTemplate('registration/email_enable_user.html.twig')
            ->context(
                [
                    'expiration_date' => $user->getTokenAt(),
                    'username' => $user->getUsername(),
                    'url' => $url,
                ]
            )
        ;
        $this->mailer->send($email);
    }

    public function sendResetPasswordLink(User $user, string $url)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->containerBagInterface->get('from_address'), 'WebMaster SnowTricks'))
            ->to(new Address($user->getEmail()))
            ->subject('Reset Password of your SnowTricks Account')
            ->htmlTemplate('forgotten_reset_password/email_reset_password.html.twig')
            ->context(
                [
                    'expiration_date' => $user->getTokenAt(),
                    'username' => $user->getUsername(),
                    'url' => $url,
                ]
            )
        ;
        $this->mailer->send($email);
    }
}