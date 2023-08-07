<?php

namespace App\Tests\Tasks;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DoneTaskTest extends WebTestCase
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

    public function testListDoneAction()
    {
        $this->loginUser();
        $this->client->request('GET', '/tasks/done');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
