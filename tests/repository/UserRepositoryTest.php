<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 31/10/2020
 * Time: 15:19
 */
class UserRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCountUsers()
    {
        self::bootKernel();
        $this->loadFixtures([UserFixtures::class]);
        $users = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(10, $users);
    }

}