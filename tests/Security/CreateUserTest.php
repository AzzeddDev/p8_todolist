<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginAdmin($username='AdminZoe', $password='1234'): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['_username' => $username, '_password' => $password]);
    }

    public function testCreateUser()
    {
        $this->loginAdmin();

        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'autre';
        $form['user[password][first]'] = 'autre';
        $form['user[password][second]'] = 'autre';
        $form['user[email]'] = 'autre@p8.com';
        $form['user[roles]'] = 'ROLE_USER';
        $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
