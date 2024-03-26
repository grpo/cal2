<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Service\EntityUpdaterService;
use App\Service\JsonValidator;
use App\Service\ValidatorViolationAggregator;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/recipes', name: 'app_recipes_')]
class RecipeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $recipes = $entityManager->getRepository(Recipe::class)->findAll();
        if (!$recipes) {
            return new JsonResponse();
        }
        $payload = json_decode($serializer->serialize($recipes, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        string $id,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Request $request
    ): JsonResponse {
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $payload = json_decode($serializer->serialize($recipe, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        ValidatorViolationAggregator $validatorViolationAggregator,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        JsonValidator $jsonValidator,
        Request $request
    ): JsonResponse {
        $requestContent = $jsonValidator->validate($request->getContent());
        $recipe = $serializer->deserialize($requestContent, Recipe::class, 'json');
        $errors = $validator->validate($recipe);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($recipe);
        $entityManager->flush();
        $payload = json_decode($serializer->serialize($recipe, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(
        string $id,
        Request $request,
        ValidatorViolationAggregator $validatorViolationAggregator,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        JsonValidator $jsonValidator,
        EntityUpdaterService $entityUpdaterService,
    ): JsonResponse {
        $requestContent = $jsonValidator->validate($request->getContent());
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $updatedRecipe = $serializer->deserialize($requestContent, Recipe::class, 'json');
        $updatedRecipe = $entityUpdaterService->update($recipe, $updatedRecipe);

        $errors = $validator->validate($updatedRecipe);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($recipe);
        $entityManager->flush();
        $payload = json_decode($serializer->serialize($recipe, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        string $id,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($recipe);
        // TODO remove RecipeProduct
        $entityManager->flush();

        return new JsonResponse();
    }
}
