<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 03/11/2020
 * Time: 08:55
 */

namespace App\Tests\Controller;


use App\DataFixtures\FigureFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FigureControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testLetAuthenticateUserAccessDeleteFigure(){
        $users = $this->loadFixtures([UserFixtures::class]);
        $tricks =  $this->loadFixtures([FigureFixtures::class]);

        dd($users, $tricks);

    }

}