<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(GameRepository $repo): Response
    {
        $games = $repo->findAll();
        dump($games); // équivalent var_dump
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games
        ]);
    }

    #[Route('/game/new', name: 'app_add_game')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $newgame = new Game;
        $form = $this->createForm(GameType::class, $newgame)
        ->add('save', SubmitType::class)
        ->add('reset', ResetType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $newgame = $form->getData();
            $entityManager->persist($newgame);
            $entityManager->flush();
        }
        
        return $this->render('game/add.html.twig', [
            'form' => $form
        ]);
    }

    // le fait d'appeler la route déclenche la fonction
    #[Route('/game/{id}', name: 'app_show_game')]
    public function show(GameRepository $repo, $id): Response
    {
        $game = $repo->find($id);

        return $this->render('game/show.html.twig', [
            'game' => $game
        ]);
    }
}
