<?php

namespace App\Tests\Tasks;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateTaskTest extends WebTestCase
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


    public function testModifyAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/tasks/12/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Un nouveau titre';
        $form['task[content]'] = 'Un nouveau Lorem Ipsum';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    public function testNoneTaskModifyAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/tasks/11/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
}
