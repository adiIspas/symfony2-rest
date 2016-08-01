<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 8/1/2016
 * Time: 10:06 AM
 */

namespace AppBundle\Test;

use GuzzleHttp\Client;

class ApiTestCase extends \PHPUnit_Framework_TestCase
{

    public static $staticClient;

    /**
     * @var Client
     */
    protected $client;


    public static function setUpBeforeClass()
    {
        self::$staticClient = new Client([
            'base_url' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function setup()
    {
        $this->client = self::$staticClient;
    }

}