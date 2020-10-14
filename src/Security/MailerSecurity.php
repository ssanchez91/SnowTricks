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
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerSecurity
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendActivationLink(User $user, string $url)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('s.sanchez@infinivox.fr', 'WebMaster SnowTricks'))
            ->to(new Address($user->getEmail()))
            ->subject('Activation de votre compte SnowTricks')
            ->htmlTemplate('registration/enable_user.html.twig')
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