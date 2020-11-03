<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 01/11/2020
 * Time: 16:04
 */

namespace App\Tests\Entity;


use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserEntityTest extends KernelTestCase
{
    use FixturesTrait;

    public function testValidateUserEntity()
    {
        $this->assertHasErrors($this->createUserEntity(), 0);
    }

    public function testNoValidatePasswordUserEntity()
    {
        $this->assertHasErrors($this->createUserEntity()->setPassword('2a'), 1);
    }

    public function testNoValidateUsernameUserEntity()
    {
        $this->loadFixtures([UserFixtures::class]);
        $this->assertHasErrors($this->createUserEntity()->setUsername('user_5'), 1);
        $this->assertHasErrors($this->createUserEntity()->setUsername(''), 2);
    }

    public function testNoValidateEmailUserEntity()
    {
        $this->assertHasErrors($this->createUserEntity()->setEmail('test-test.com'), 1);
        $this->assertHasErrors($this->createUserEntity()->setEmail(''), 1);
    }

    public function testNoValidateFirstNameUserEntity()
    {
        $this->assertHasErrors($this->createUserEntity()->setFirstName(''), 1);
    }

    public function testNoValidateLastNameUserEntity()
    {
        $this->assertHasErrors($this->createUserEntity()->setLastName(''), 1);
    }

    public function testNoValidatePathLogoUserEntity(){
        $this->assertHasErrors($this->createUserEntity()->setPathLogo('doc.pdf'), 1, ['Default','phpUnitTest']);
    }

    /**
     * @return User
     */
    private function createUserEntity():User
    {
        return (new User())
            ->setFirstName('Steeve')
            ->setLastName('Sanchez')
            ->setUsername('ssanchez')
            ->setPassword('Test!#1234')
            ->setRoles(['ROLE_USER'])
            ->setEmail('sanchez.steeve@gmail.com')
            ->setEnabled(false)
            ->setTokenAt(new \DateTime('+ 1day'))
            ->setToken('####MONTOKENDETEST####')
            ->setPathLogo("logo-admin.png");
    }

    /**
     * @param User $user
     * @param int $number
     * @param array $groups
     */
    public function assertHasErrors(User $user, int $number = 0, $groups = ['Default'])
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user, $constraints = null, $groups );
        $messages =[];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error)
        {
            $messages[] = $error->getPropertyPath(). ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }
}