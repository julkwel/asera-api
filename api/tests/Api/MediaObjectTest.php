<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 30/12/2023
 */

namespace App\Tests\Api;

use App\Entity\MediaObject;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MediaObjectTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateAMediaObject(): void
    {
        $file = new UploadedFile('fixtures/Media/img.png', 'image.png');
        $client = self::createClient();

        $client->request('POST', '/media_objects', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'parameters' => [],
                'files' => [
                    'file' => $file,
                ],
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceItemJsonSchema(MediaObject::class);
    }
}