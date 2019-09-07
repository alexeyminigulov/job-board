<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\DataFixtures\DataFixtureTestCase;

class SecurityControllerTest extends DataFixtureTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Login', $crawler->filter('.container h1')->text());
    }

    public function testLoginValid()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['login_form[_username]'] = 'admin23@mail.ru';
        $form['login_form[_password]'] = '1q2w3e4r5t';

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Welcome to Symfony 3.4.27', $crawler->filter('#container h1')->text());
    }

    public function testLoginInvalidPassword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['login_form[_username]'] = 'admin23@mail.ru';
        $form['login_form[_password]'] = 'invalidpassword';

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Invalid credentials', $crawler->filter('.block-login-form__errors')->text());
    }

    public function testLoginInvalidUserName()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['login_form[_username]'] = 'invalidusername';
        $form['login_form[_password]'] = '1q2w3e4r5t';

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Username could not be found.', $crawler->filter('.block-login-form__errors')->text());
    }
}
