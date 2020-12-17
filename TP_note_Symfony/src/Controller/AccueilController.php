<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'mostRecentProducts' => $productRepository->findMostRecent(5),
            'cheapestProducts' => $productRepository->findCheapest(5)
        ]);
    }
}