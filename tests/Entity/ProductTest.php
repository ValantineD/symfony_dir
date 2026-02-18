<?php

namespace Entity;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    public function testValidEntity(): void
    {
        self::bootKernel();

        $category = new Category();
        $category->setTitle('Shoes');

        $product = new Product();
        $product->setTitle('Sneaker')
            ->setDescription('voila ma description')
            ->setPrice(199.99)
            ->setCategory($category);

        $error = self::getContainer()->get('validator')->validate($product);
        $this->assertEquals(0, $error->count());
    }

    public function testValidEntityEmptyData(): void
    {
        self::bootKernel();

        $category = new Category();
        $category->setTitle('');

        $product = new Product();
        $product->setTitle('')
            ->setDescription('')
            ->setPrice(null)
            ->setCategory(null);

        $error = self::getContainer()->get('validator')->validate($product);
        $this->assertEquals(4, $error->count());
    }
}

