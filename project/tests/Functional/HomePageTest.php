<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectLink('S\'inscrire');
        $this->assertCount(1, $button);

        $recipes = $crawler->filter('.card');
        $this->assertCount(3,$recipes);

        $this->assertSelectorTextContains('h1', 'Symfony recipes');
    }
}
