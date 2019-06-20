<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        /** @var Response $response */
        $response = $client->getResponse();
        $crawler = $client->getCrawler();

        // page is working
        $this->assertEquals(200, $response->getStatusCode());

        // page content <= 10 posts
        $this->assertLessThanOrEqual(11, $crawler->filter('html .index-posts .post-list-view')->count());

        // posts order: created date ASC
        $postDates = [];
        $crawler->filter('html .index-posts .post-list-view .publication-date .date')
            ->each(function  ($crawler) use (&$postDates) {
                $postDates[] = $crawler->text();
        });

        for ($i = 0; $i < count($postDates) - 2; ++$i) {
            $this->assertGreaterThanOrEqual($postDates[$i + 1], $postDates[$i]);
        }
    }
}