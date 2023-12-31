<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 30/12/2023
 */

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Company;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CompanyTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateCompanies(): void
    {
        static::createClient()->request('POST', '/companies', [
            'json' => [
                'name' => 'bonbon',
                'address' => 'Tana',
                'contacts' => [
                    [
                        'email' => 'test@company.com',
                        'phone' => ['097 876 76', '097 876 78', '097 876 77',],
                        'web' => 'company.com'
                    ]
                ]
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertMatchesResourceItemJsonSchema(Company::class);
    }
}