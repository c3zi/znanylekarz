<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 14:29
 */

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Model\UserRepositoryInMemory;


class UserControllerTest extends WebTestCase
{
    private $client;

    private $users;

    public function setUp()
    {
        $this->client = static::createClient(array());
    }

    /**
     * @test
     */
    public function jsonGetUserAction()
    {
        $this->client->request('GET', '/api/users/1', array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    public function jsonGetUserActionForUnexistedId()
    {
        $this->client->request('GET', '/api/users/11212', array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 500);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    /**
     * @test
     */
    public function jsonPostUserAction()
    {
        $this->client->request(
            'POST',
            '/api/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"example_name","email":"bob@example.com"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    /**
     * @test
     */
    public function jsonPostUserWithWrongDataAction()
    {
        $this->client->request(
            'POST',
            '/api/users',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"example_name","email":""}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    /**
     * @test
     */
    public function jsonPutUserAction()
    {
        $this->client->request(
            'PUT',
            '/api/users/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"email":"new@example.com"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    /**
     * @test
     */
    public function jsonPutUserWithWrongData()
    {
        $this->client->request(
            'PUT',
            '/api/users/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"email":""}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true)
    {
        $contentType = 'application/json';

        $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());

        $this->assertTrue($response->headers->contains('Content-Type', $contentType), $response->headers);

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}