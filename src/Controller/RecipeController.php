<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Service\EntityUpdaterService;
use App\Service\ValidatorViolationAggregator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/recipes', name: 'app_recipes_')]
class RecipeController extends AbstractApiController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $recipes = $this->entityManager->getRepository(Recipe::class)->findAll();
        if (!$recipes) {
            return new JsonResponse();
        }
        $payload = json_decode($this->serializer->serialize($recipes, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        string $id,
        Request $request
    ): JsonResponse {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $payload = json_decode($this->serializer->serialize($recipe, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        ValidatorViolationAggregator $validatorViolationAggregator,
        ValidatorInterface $validator,
        Request $request
    ): JsonResponse {
        $validJson = $this->jsonValidator->validate($request->getContent());
        $recipe = $this->serializer->deserialize($validJson, Recipe::class, 'json');
        $errors = $validator->validate($recipe);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
        $payload = json_decode($this->serializer->serialize($recipe, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(
        string $id,
        Request $request,
        ValidatorViolationAggregator $validatorViolationAggregator,
        ValidatorInterface $validator,
        EntityUpdaterService $entityUpdaterService,
    ): JsonResponse {
        $validJson = $this->jsonValidator->validate($request->getContent());
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $updatedRecipe = $this->serializer->deserialize($validJson, Recipe::class, 'json');
        $updatedRecipe = $entityUpdaterService->update($recipe, $updatedRecipe);

        $errors = $validator->validate($updatedRecipe);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
        $payload = json_decode($this->serializer->serialize($recipe, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        string $id,
    ): JsonResponse {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);
        if (!$recipe) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($recipe);
        // TODO remove RecipeProduct
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
