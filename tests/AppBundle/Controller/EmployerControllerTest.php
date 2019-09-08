<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\DataFixtures\DataFixtureTestCase;

class EmployerControllerTest extends DataFixtureTestCase
{
    public function testSignup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/employer/signup');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Регистрация работодателя', $crawler->filter('.container h2')->text());
    }

    public function testCreateJob()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['login_form[_username]'] = 'employer@mail.ru';
        $form['login_form[_password]'] = '1q2w3e4r5t';

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/employer/job/create');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Создать вакансию', $crawler->filter('.container h2')->text());
    }

    public function testAnonymousTryOpenCreateJob()
    {
        $client = static::createClient();

        $client->request('GET', '/employer/job/create');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Login', $crawler->filter('.container h1')->text());
    }

    public function testAuthenticatedUserTryOpenSignUp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['login_form[_username]'] = 'employer@mail.ru';
        $form['login_form[_password]'] = '1q2w3e4r5t';

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Welcome to Symfony 3.4.27', $crawler->filter('#container h1')->text());

        $client->request('GET', '/employee/signup');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertContains('Welcome to Symfony 3.4.27', $crawler->filter('#container h1')->text());
    }
}
