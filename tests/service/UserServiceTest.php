<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 02/11/2020
 * Time: 11:29
 */

namespace App\Tests\Service;


use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
{
    use FixturesTrait;

    public function testValidateCheckTokenMethod()
    {
        $this->hasAssertError();
    }

    public function testNoValidateTokenCheckTokenMethod()
    {
        $this->hasAssertError(false, '+ 1day', 'Invalide_token');
    }

    public function testNoValidateTokenAtCheckTokenMethod()
    {
        $this->hasAssertError(false, '- 1day');
    }

    public function hasAssertError($result = true, $token_at = '+ 1day', $token = 'good_token')
    {
        self::bootKernel();
        $this->loadFixtures([UserFixtures::class]);
        $user = self::$container->get(UserRepository::class)->findOneBy(['username' => 'user_1']);
        $user->setToken('good_token');
        $user->setTokenAt(new \DateTime($token_at));
        $userService = self::$container->get(UserService::class);
        $this->assertEquals( $result, $userService->checkToken($user, $token));
    }

}