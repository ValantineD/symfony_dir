<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ProductRepository $productRepository): Response
    {
        return $this->render('home.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/product/{id}', name: 'app_public_product_show', methods: ['GET'])]
    public function product_show(Product $product): Response
    {
        return $this->render('product_show.html.twig', [
            'product' => $product
        ]);
    }
}
