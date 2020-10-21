<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public const USER_REFERENCE = 'user_';

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $user->setFirstName(self::USER_REFERENCE.$i);
            $user->setLastName(self::USER_REFERENCE.$i);
            $user->setUsername(self::USER_REFERENCE.$i);
            $user->setPassword($this->passwordEncoder->encodePassword($user,self::USER_REFERENCE.$i));
            $user->setEmail(self::USER_REFERENCE.$i.'@yopmail.fr');
            $user->setPathLogo("logo/logo-admin.png");
            $user->setRoles(['ROLE_USER']);
            $user->setToken(self::USER_REFERENCE.$i);
            $user->setTokenAt(new \DateTime('+2 Hours'));
            $user->setEnabled(true);
            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE.$i, $user);

        }

        $manager->flush();
    }
}
