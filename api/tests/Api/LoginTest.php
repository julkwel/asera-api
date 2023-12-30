<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 30/12/2023
 */

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LoginTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testLogin()
    {
        static::createClient()->request('POST', '/users', [
            'json' => [
                'lastname' => 'Kévin',
                'username' => 'user@asera.com',
                'plainPassword' => '@theP*ss2023'
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'password' => '@theP*ss2023',
                'username' => 'user@asera.com'
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $responseToArray = json_decode($this->getClient()->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseToArray);
    }
}