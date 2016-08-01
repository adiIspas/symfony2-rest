<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 8/1/2016
 * Time: 9:41 AM
 */

namespace AppBundle\Tests\Controller\API;


use AppBundle\Test\ApiTestCase;

class ProgrammerControllerTest extends ApiTestCase
{
    public function testPOST()
    {
        $nickname = 'ObjectOrienter';

        $data = array(
            'nickname' => $nickname,
            'avatarNumber' => 5,
            'tagLine' => 'a test dev!'
        );

// 1) Create a programmer resource
        $response = $this->$client->post('/api/programmers', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201,$response->getStatusCode());
        $this->assertEquals('/api/programmers/ObjectOrienter',$response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('nicknamne',$finishedData);
        $this->assertEquals('ObjectOrienter', $data['nickname']);
    }
}
