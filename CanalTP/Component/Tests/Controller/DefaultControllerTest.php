<?php

namespace CanalTP\MediaManagerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $count = $crawler->filter('html:contains("Hello")')->count();
        $this->assertTrue($count > 0);
    }
}
