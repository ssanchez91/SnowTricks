<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 02/11/2020
 * Time: 11:58
 */

namespace App\Tests\Controller;


use App\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationControllerTest extends WebTestCase
{
    use FixturesTrait;
    private $client;

    public function setUp(){
        $this->client = static::createClient();
    }

    public function testRegisterPage()
    {
        $this->client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testValidationRegistrationForm()
    {
        $this->loadFixtures([UserFixtures::class]);
        $crawler = $this->client->request('GET', '/register');
        $form =  $this->makeForm($crawler->selectButton('Register'));
        $this->client->submit($form);
        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-primary');
    }

    private function makeForm($buttonCrawlerNode)
    {
        $photo = new UploadedFile(
            'C:\\wamp64\\www\\SnowTricks\\public\\assets\\img\\logo\\banana-5f90142b2bb86.png',
            'banana-5f90142b2bb86.png',
            'image/png',
            null
        );

        return $buttonCrawlerNode->form(
            [
                'registration_form[pathLogo]' => $photo,
                'registration_form[firstName]' => 'user_test1',
                'registration_form[lastName]' => 'user_test1',
                'registration_form[username]' => 'user_test_1',
                'registration_form[email]' => 'user_test1@yopmail.fr',
                'registration_form[password][first]' =>'Test!1234',
                'registration_form[password][second]'=>'Test!1234',
                'registration_form[agreeTerms]' => true
            ]);
    }
}