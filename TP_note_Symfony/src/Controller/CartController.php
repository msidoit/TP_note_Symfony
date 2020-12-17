<?php

namespace App\Controller;

use App\Entity\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Formulaire\CommandType;
use App\Repository\ProductRepository;
use App\Formulaire\RegisterType;
use Symfony\Component\HttpFoundation\Request;
class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, Request $request)
    {
        $cart = $session->get('panier', []);
        $products = [];
        foreach ($cart as $id => $quantity) {
            $productRepository = $this->getDoctrine()->getRepository(Product::class);
            $product = $productRepository->find($id);
            $product->quantity = $quantity;
            $products[] = $product;
        }
        $command = new Command();
        $commandForm = $this->createForm(CommandType::class, $command);
        $commandForm->remove('createdAt');
        $commandForm->remove('products');
        $commandForm->handleRequest($request);
        if ($commandForm->isSubmitted() && $commandForm->isValid())
        {
            $command->setCreatedAt(new \DateTime);
            foreach ($cart as $id => $quantity) {
                $productRepository = $this->getDoctrine()->getRepository(Product::class);
                $product = $productRepository->find($id);
                $command->addProduct($product);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($command);
            $entityManager->flush();
            $this->addFlash('success', "La commande a bien été créée");
             return $this->redirectToRoute('cart');
               
        }
        
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'products' => $products,
            'commandForm' => $commandForm->createView()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart.add")
     */
    public function add($id, SessionInterface $session)
    {
        $cart = $session->get('panier', []);
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'nok'], $status = 404);
        } else {
            $cart[$id] = 1;
            $session->set('panier', $cart);
            return $this->json(['message' => 'ok'], $status = 200);
        }
    }

    /**
     * @Route("/cart/delete/{id}", name="cart.delete")
     */
    public function delete($id, SessionInterface $session)
    {
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);
        if(!$product){
            throw $this->createNotFoundException('Ce produit n\'existe n\'existe pas');
        }
        else{
            $cart = $session->get('panier', []);
            unset($cart[$id]);
            $session->set('panier', $cart); 
            $this->addFlash('success', "L'article {$product->getName()} a bien été supprimé !");       
            return $this->redirectToRoute('cart');
        }
    }
}