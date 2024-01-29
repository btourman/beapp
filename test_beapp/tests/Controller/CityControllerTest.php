<?php

namespace App\Test\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

class CityControllerTest extends ApiTestCase
{
    private string $path = '/city';
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $client = static::createClient();
        $response = $client->request('POST', '/api/login_check', ['json' => [
            'username' => 'admin@tap_and_go.com',
            'password' => 'password',
        ]]);
        $json = json_decode($response->getContent());
        $this->client = static::createClient(defaultOptions: ['headers' => ['Authorization' => 'Bearer '.$json->token]]);
    }

    public function testGetAllCitiesOk(): void
    {
        $response = $this->client->request('GET', $this->path);

        $this->assertResponseIsSuccessful();

        $this->assertJsonStringEqualsJsonFile('tests/Responses/get_all_cities_ok.json', $response->getContent());
    }

    public function testGetCityByIdOk(): void
    {
        $response = $this->client->request('GET', $this->path.'/018d3c56-bb85-76b5-a471-c1ba8bc0cc2c');

        $this->assertResponseIsSuccessful();

        $this->assertJsonStringEqualsJsonFile('tests/Responses/get_city_ok.json', $response->getContent());
    }

    public function testUpdateCityOK(): void
    {
        $response = $this->client->request('PATCH', $this->path.'/018d3c56-bb85-76b5-a471-c1ba8bc0cc2c',
            ['json' => [
                'name' => 'Angers, la nouvelle ville',
                'status' => true,
            ]]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonStringEqualsJsonFile('tests/Responses/update_city_ok.json', $response->getContent());
    }

}
