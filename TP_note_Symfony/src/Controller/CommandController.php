<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Command;
use App\Repository\CommandRepository;
class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command")
     */
    public function index(): Response
    {
        $commandRepository = $this->getDoctrine()->getRepository(Command::class);
        $commands = $commandRepository->findAll();
        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
            'commands' => $commands
        ]);
    }

    /**
     * @Route("/command/{id}", name="command.show")
     */
    public function show($id){
        $commandRepository = $this->getDoctrine()->getRepository(Command::class);
        $command=$commandRepository->find($id);

        if(!$command){
            throw $this->createNotFoundException('Cette commande n\'existe pas');
        }
        else{
            return $this->render('command/show.html.twig', [
                'controller_name' => 'CommandController',
                'command' => $command,
                'products' => $command->getProducts(),
                'nbproducts' => count($command->getProducts())
            ]);
        }
    }
}