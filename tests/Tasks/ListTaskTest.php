<?php

namespace App\Tests\Tasks;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListTaskTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginUser($username='John', $password='1234'): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['_username' => $username, '_password' => $password]);
    }

    // Tester les cas d'accès a la page "List Tasks page" //
    public function testLoginOkUser()
    {
        $this->loginUser();
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('http://localhost/');
    }

    public function testAccessListPageTaskUser()
    {
        $this->client->request('GET', '/tasks');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('http://localhost/login');
        $this->loginUser();
        $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h4', "titre 1");
    }

    public function testLoginKo()
    {
        $this->loginUser('BlaBla');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('http://localhost/login');
    }
}
