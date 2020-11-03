<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 02/11/2020
 * Time: 12:18
 */

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    private $client;

    public function setUp(): void
    {
         $this->client = static::createClient();
    }

    public function testDisplayLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredential(){

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user_1',
            'password' => 'test'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessFullLogin()
    {
        $this->loadFixtures([UserFixtures::class]);
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user_1',
            'password' => 'user_1'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/home');
    }
}