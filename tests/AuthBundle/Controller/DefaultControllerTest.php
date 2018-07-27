<?php

namespace AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testRegistrationWithIncompleteFields()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/auth/register', [
            'name' => '',
            'email' => '',
            'password' => ''
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), 422);
    }

    public function testRegistration()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/auth/register', [
            'name' => 'john doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'password'
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        //$this->assertArrayHasKey('data', $data);
    }

    public function testRegistrationWithExistingEmail()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/auth/register', [
            'name' => 'john doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'password'
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), 422);
    }

    public function testLoginWithWrongCredentials(){
        $client = static::createClient();

        $crawler = $client->request('POST', '/auth/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'password123'
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), 401);
    }

    public function testLoginWithRightCredentials(){
        $client = static::createClient();

        $crawler = $client->request('POST', '/auth/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'password'
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data["data"]);
    }
}
