<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        //Récupérer le formulaire
        $submitButton = $crawler->selectButton('Envoyer');
        $form = $submitButton->form();

        $form["contact[fullName]"] = "Jean Dupont";
        $form["contact[email]"] = "jd@symrecipe.com";
        $form["contact[subject]"] = "Test";
        $form["contact[message]"] = "Test";

        //Soumettre le formulaire
        $client->submit($form);

        //Vérifier le status HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        //Vérifier l'envoie du mail
        $this->assertEmailCount(1);

        $client->followRedirect();

        //Vérifier la présence du message de succès
        $this->assertSelectorTextContains(
            '.alert.alert-dismissible.alert-success',
            'Votre mail a été transmis'
        );
    }
}
