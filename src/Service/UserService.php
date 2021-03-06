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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
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
     * @var RouterInterface
     */
    private $routerInterface;
    /**
     * @var FileUploaderService
     */
    private $fileUploaderService;

    /**
     * @param EntityManagerInterface $entityManagerInterface
     * @param UserPasswordEncoderInterface $userPasswordEncoderInterface
     * @param TokenGeneratorInterface $generatorInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface, UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoderInterface, TokenGeneratorInterface $generatorInterface, RouterInterface $routerInterface, FileUploaderService $fileUploaderService){

        $this->generatorInterface = $generatorInterface;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->userRepository = $userRepository;
        $this->routerInterface = $routerInterface;
        $this->fileUploaderService = $fileUploaderService;
    }

    /**
     * createUser
     * 
     * @param User $user
     * @param $password
     * @return User
     */
    public function createUser(User $user, string $password, Request $request ): User
    {
        $user->setPathLogo($this->uploadLogo($request));
        $user->setPassword($this->createPassword($user, $password));
        $user->setRoles(['ROLE_USER']);
        return $this->addToken($user);
    }

    /**
     * createToken
     * 
     * @param User $user
     * @return string
     */
    public function createToken(User $user): string
    {
        return $this->generatorInterface->generateToken();
    }

    /**
     * createToken
     * 
     * @param User $user
     * @param string $password
     * @return string
     */
    public function createPassword(User $user, string $password): string
    {
        return $this->userPasswordEncoderInterface->encodePassword($user, $password);
    }

    /**
     * checkTokenToRegister
     * 
     * @param User $user
     * @param string $token
     * @return boolean
     */
    public function checkTokenToRegister(User $user, string $token): bool
    {
        if($this->checkToken($user, $token) === true)
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

    /**
     * checkTokenToRegister
     * 
     * @param User $user
     * @param string $token
     * @return boolean
     */
    public function checkToken(User $user, string $token): bool
    {
        $tokenAt = $user->getTokenAt();

        if (null !== $token && $user->getToken() === $token && time() < $tokenAt->getTimestamp())
        {
            return true;
        }

        return false;
    }

    /**
     * addToken
     *
     * @param User $user
     * @return User
     */
    public function addToken(User $user): User
    {
        $user->setToken($this->createToken($user));
        $user->setTokenAt(new \DateTime('+2 Hours'));

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        return $user;
    }

    /**
     * getUrlToEmail
     *
     * @param string $route
     * @param User $user
     * @return string
     */
    public function getUrlToEmail(string $route, User $user): string
    {
        // generate a signed url to enable user
        return $this->routerInterface->generate(
            $route,
            [
                'username' => $user->getUsername(),
                'token' => $user->getToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * uploadLogo
     *
     * @param Request $request
     * @return String
     */
    public function uploadLogo(Request $request): String
    {
        /** @var UploadedFile $logo */
        $logo = $request->files->get('registration_form')['pathLogo'];

        return $this->fileUploaderService->upload($logo, 'logo');
    }

}