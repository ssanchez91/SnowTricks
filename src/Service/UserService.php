<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 14/10/2020
 * Time: 13:36
 */

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @var TokenGeneratorInterface
     */
    private $generatorInterface;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoderInterface;
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGeneratorInterface;

    /**
     * @param EntityManagerInterface $entityManagerInterface
     * @param UserPasswordEncoderInterface $userPasswordEncoderInterface
     * @param TokenGeneratorInterface $generatorInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface, UserPasswordEncoderInterface $userPasswordEncoderInterface, TokenGeneratorInterface $generatorInterface){

        $this->generatorInterface = $generatorInterface;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * @param User $user
     * @param $password
     * @return User
     */
    public function createUser(User $user, $password): User
    {
        $user->setPassword($this->createPassword($user, $password));
        $user->setToken($this->createToken($user));
        $user->setTokenAt(new \DateTime('+2 Hours'));
        $user->setRoles(['ROLE_USER']);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        return $user;
    }

    /**
     * @param User $user
     * @return string
     */
    public function createToken(User $user): string
    {
        return $this->generatorInterface->generateToken();
    }

    /**
     * @param User $user
     * @param string $password
     * @return string
     */
    public function createPassword(User $user, string $password): string
    {
        return $this->userPasswordEncoderInterface->encodePassword($user, $password);
    }

    /**
     * @param User $user
     * @param string $token
     * @return boolean
     */
    public function checkToken(User $user, string $token): bool
    {
        $tokenAt = $user->getTokenAt();

        if (null !== $token && $user->getToken() === $token && time() < $tokenAt->getTimestamp())
        {
            $user->setEnabled(true);
            $this->entityManagerInterface->persist($user);
            $this->entityManagerInterface->flush();

            return true;
        }

        $this->entityManagerInterface->remove($user);
        $this->entityManagerInterface->flush();

        return false;
    }
}