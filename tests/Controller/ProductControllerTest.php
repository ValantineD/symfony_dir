<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testProductController(): void
    {

        $client = static::createClient();

        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/new');

        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form();

        $form['product[category]']->select('Patalons');

        $client->submit($form, [
            'product[title]' => 'JPP',
            'product[description]' => 'Symfony rocks!',
            'product[price]' => 999,
        ]);

        $client->submit($form);

        $container = self::getContainer();
        $product = $container->get(ProductRepository::class)->findOneBy(['title' => 'JPP']);
        self::$id = $product->getId();

        $this->assertResponseRedirects('/admin/product');
    }

    public function testEditProduct(): void
    {

        $client = static::createClient();

        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/' . self::$id . '/edit');

        $buttonCrawlerNode = $crawler->selectButton('Update');
        $form = $buttonCrawlerNode->form();

        $form['product[category]']->select('Chaussettes');

        $client->submit($form, [
            'product[title]' => 'Aled',
            'product[description]' => 'Siouplait',
            'product[price]' => 666,
        ]);

        $client->submit($form);


        $this->assertResponseRedirects('/admin/product');
    }
}
