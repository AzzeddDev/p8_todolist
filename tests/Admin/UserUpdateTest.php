<?php

namespace App\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserUpdateTest extends WebTestCase
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

    public function testUpdateAction()
    {
        $this->loginAdmin();

        $crawler = $this->client->request('GET', '/users/6/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'New Name User';
        $form['user[password][first]'] = '1234';
        $form['user[password][second]'] = '1234';
        $form['user[email]'] = 'emailchangetest@nouveau.org';
        $form['user[roles]'] = 'ROLE_USER';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }
}
