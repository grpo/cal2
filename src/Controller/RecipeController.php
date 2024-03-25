<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipes', name: 'app_recipes_')]
class RecipeController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $recipes = $entityManager->getRepository(Recipe::class)->findAll();

        return new JsonResponse();
    }
}
