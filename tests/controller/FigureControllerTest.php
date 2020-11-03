<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 03/11/2020
 * Time: 09:33
 */

namespace App\Tests\Controller;


use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\CommentFixtures;
use App\DataFixtures\FigureFixtures;
use App\DataFixtures\MovieFixtures;
use App\DataFixtures\PictureFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class FigureControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testFailedAuthenticateUserAccessDeleteFigure()
    {
        $client = $this->createClient();
        $client->request('GET', '/delete/indy');
        $this->assertResponseRedirects('/login');
    }

    public function testGoodAuthenticateUserAccessDeleteFigure()
    {
        $client = $this->createClient();

        $this->loadFixtures([UserFixtures::class, FigureFixtures::class, CommentFixtures::class, CategoryFixtures::class, PictureFixtures::class, MovieFixtures::class]);

        /** @var@ User $user */
        $user = self::$container->get(UserRepository::class)->find(7);
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        $client->request('GET', '/delete/indy');
        $this->assertResponseRedirects('/home');

    }


}