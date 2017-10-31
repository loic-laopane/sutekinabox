<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerControllerTest extends WebTestCase
{
    public function testDashboard()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/dashboard');
    }

}
