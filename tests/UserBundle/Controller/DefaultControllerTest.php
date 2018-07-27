<?php

namespace UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AuthBundle\Traits\JwtTrait;


class DefaultControllerTest extends WebTestCase
{
    use JwtTrait;

    private $token;

    private function createToken($role){
        return $this->createJwtToken([
            "id" => 1,
            "role" => $role,
            "email" => "john@doe.com"
        ]);
    }

    public function testAdminCanAccessAllUsers(){
        $token = $this->createToken('ROLE_ADMIN');
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json',
        ];
        $client = static::createClient();
        $crawler = $client->request('GET', '/users', [], [], $headers);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
    }

    public function testAdminCanAccessSpecificUserData(){
        $token = $this->createToken('ROLE_ADMIN');
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json',
        ];
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/1', [],  [], $headers);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('name', $data["data"]);
    }

    public function testUserCanAccessOwnData(){
        $token = $this->createToken('ROLE_USER');
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json',
        ];
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/1', [],  [], $headers);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('name', $data["data"]);
    }

    public function testUserCannotAccessOtherUserData(){
        $token = $this->createToken('ROLE_USER');
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json',
        ];
        $client = static::createClient();
        $crawler = $client->request('POST', '/auth/register', [
            'name' => 'john doe',
            'email' => 'johndoe1@gmail.com',
            'password' => 'password'
        ]);
        $client2 = static::createClient();
        $crawler = $client2->request('GET', '/users/2', [],  [],  $headers);
        $this->assertEquals($client2->getResponse()->getStatusCode(), 401);
    }
}
