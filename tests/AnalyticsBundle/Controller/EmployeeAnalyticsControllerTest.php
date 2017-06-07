<?php

namespace AnalyticsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeAnalyticsControllerTest extends WebTestCase
{
    public function testMostFree()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/analytics/employees/most-free');

        $this->assertContains(json_encode([]), $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/api/analytics/employees/most-free', ['technologies' => '1,2']);

        $json = json_encode([
            [
                "id" => 2,
                "name" => "Grygory Reshetnyak",
                "position" => "Frontend Developer",
                "skills" => [
                    [
                        "id" => 2,
                        "title" => "JavaScript"
                    ]
                ],
                "status" => "available",
                "project_count" => "0"
            ],
            [
                "id" => 1,
                "name" => "Oleksii Bychek",
                "position" => "PHP Developer",
                "skills" => [
                    [
                        "id" => 1,
                        "title" => "PHP"
                    ]
                ],
                "status" => "available",
                "project_count" => "0"
            ]
        ]);
        $this->assertContains($json, $client->getResponse()->getContent());
    }
}

