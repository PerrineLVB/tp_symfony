<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/new', name: 'app_add_category')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $newcat = new Category;
        $form = $this->createForm(CategoryType::class, $newcat)
            ->add('save', SubmitType::class)
            ->add('reset', ResetType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // récupère les données du formulaire
            $newcat = $form->getData();

            // tell Doctrine you want to (eventually) save the Product (no queries yet) (persist = similaire à "commit" github)
            $entityManager->persist($newcat);

            // actually executes the queries (i.e. the INSERT query) (flush = similaire à "push" github)
            $entityManager->flush();
        }

        return $this->render('category/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/category/{id}', name: 'app_show_category')]
    public function show(CategoryRepository $repo, $id): Response
    {
        $cat = $repo->find($id);
        return $this->render('category/show.html.twig', [
            'category' => $cat
        ]);
    }
}
