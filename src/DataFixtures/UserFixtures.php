<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Steeve');
        $user->setLastName('SANCHEZ');
        $user->setUsername('ssanchez');
        $user->setEmail('sanchez.steeve@gmail.com');
        $user->setPathLogo("logo/logo-admin.png");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordEncoder->encodePassword($user,"Test!1234"));

        $manager->persist($user);
        $manager->flush();
    }
}
