<?php

namespace App\Tests\Functional;

use App\Entity\Ingredient;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class IngredientTest extends WebTestCase
{
    public function testIfCreateIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Recup urlgenerator
        /** @var Router $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // Recup entity manager
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        // Se rendre sur la page de création d'un ingrédient
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient.new'));

        // Gérer le formulaire
        $form = $crawler->filter('form[name=ingredient]')->form([
            'ingredient[name]' => "Un ingrédient",
            'ingredient[price]' => floatval(33),
        ]);
        $client->submit($form);

        // Gérer la redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Gérer l'alert box et la route
        $this->assertSelectorTextContains('div.alert-success', 'Votre ingrédient a été créé avec succès');

        $this->assertRouteSame('ingredient.index');
    }

    public function testIfReadIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Recup urlgenerator
        /** @var Router $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // Recup entity manager
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        // Se rendre sur la page index d'ingrédient
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient.index'));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('ingredient.index');
    }

    public function testIfUpdateIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Recup urlgenerator
        /** @var Router $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // Recup entity manager
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy([
            'user' => $user
        ]);

        $client->loginUser($user);

        // Se rendre sur la page de d'édition d'un ingrédient
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('ingredient.edit', ['id' => $ingredient->getId()]));

        $this->assertResponseIsSuccessful();

        // Gérer le formulaire
        $form = $crawler->filter('form[name=ingredient]')->form([
            'ingredient[name]' => "Un ingrédient",
            'ingredient[price]' => floatval(33),
        ]);
        $client->submit($form);

        // Gérer la redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        // Gérer l'alert box et la route
        $this->assertSelectorTextContains('div.alert-success', sprintf('Votre ingrédient %s a été modifié avec succès', $ingredient->getName()));
        $this->assertRouteSame('ingredient.index');
    }

    public function testIfDeleteIngredientIsSuccessful(): void
    {
        $client = static::createClient();

        // Recup urlgenerator
        /** @var Router $urlGenerator */
        $urlGenerator = $client->getContainer()->get('router');

        // Recup entity manager
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy([
            'user' => $user
        ]);

        $client->loginUser($user);

        // Se rendre sur la page de de suppression d'un ingrédient
        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('ingredient.delete', ['id' => $ingredient->getId()]));


        // Gérer la redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        // Gérer l'alert box et la route
        $this->assertSelectorTextContains('div.alert-success', sprintf('Ingrédient %s a été supprimé avec succès', $ingredient->getName()));
        $this->assertRouteSame('ingredient.index');
    }
}
