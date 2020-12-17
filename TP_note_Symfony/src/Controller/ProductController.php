<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $products = $productRepository->findAll();
        $product = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            10
        );

        $product->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

     /**
     * @Route("/product/{id}", name="product.show")
     */
    public function show($id){
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);

        if (!$product)
        {
            throw $this->createNotFoundException('Ce produit n\'existe pas');
        }
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ArticleController',
            'product' => $product
        ]);
    }
}
