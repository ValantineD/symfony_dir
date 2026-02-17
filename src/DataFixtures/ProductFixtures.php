<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $faker->addProvider(new FakerPicsumImagesProvider($faker));

        $desDir = dirname(__DIR__) . '/../public/uploads/pictures/';

        if (!is_dir($desDir)) {
            mkdir($desDir, 0775, true);
        } else {
            exec("rm -rf " . $desDir);
            mkdir($desDir, 0775, true);
        }


        for ($i = 0; $i <= 10; $i++) {
            $product = new Product();

            $filePath = $faker->image(dir: '/tmp');

            if ($filePath) {
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                $filename = uniqid('products_', true) . '.' . $ext;

                copy($filePath, $desDir . '/' . $filename);
                $product->setPictureFilename($filename);
            }


            $product->setTitle($faker->words(3, true))
                ->setPrice($faker->numberBetween($min = 20, $max = 300))
                ->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2))
                ->setCategory($this->getReference('category-' . rand(0, 5), Category::class));

            $manager->persist($product);
        }

        $manager->flush();
    }

    public
    function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
