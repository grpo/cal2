<?php

namespace App\Controller;

use App\Entity\RecipeProduct;
use App\Service\EntityUpdaterService;
use App\Service\ValidatorViolationAggregator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/recipe-products', name: 'app_recipe-products_')]
class RecipeProductsController extends AbstractApiController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $recipeProducts = $this->entityManager->getRepository(RecipeProduct::class)->findAll();
        if (!$recipeProducts) {
            return new JsonResponse();
        }
        $payload = json_decode($this->serializer->serialize($recipeProducts, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        string $id,
        Request $request
    ): JsonResponse {
        $recipeProduct = $this->entityManager->getRepository(RecipeProduct::class)->find($id);
        if (!$recipeProduct) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $payload = json_decode($this->serializer->serialize($recipeProduct, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        ValidatorViolationAggregator $validatorViolationAggregator,
        ValidatorInterface $validator,
        Request $request
    ): JsonResponse {
        $validJson = $this->jsonValidator->validate($request->getContent());
        $recipeProduct = $this->serializer->deserialize($validJson, RecipeProduct::class, 'json');
        $errors = $validator->validate($recipeProduct);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($recipeProduct);
        $this->entityManager->flush();
        $payload = json_decode($this->serializer->serialize($recipeProduct, 'json'));

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
        $recipeProduct = $this->entityManager->getRepository(RecipeProduct::class)->find($id);
        if (!$recipeProduct) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $updatedRecipeProduct = $this->serializer->deserialize($validJson, RecipeProduct::class, 'json');
        $updatedRecipeProduct = $entityUpdaterService->update($recipeProduct, $updatedRecipeProduct);

        $errors = $validator->validate($updatedRecipeProduct);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($recipeProduct);
        $this->entityManager->flush();
        $payload = json_decode($this->serializer->serialize($recipeProduct, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        string $id,
    ): JsonResponse {
        $recipeProduct = $this->entityManager->getRepository(RecipeProduct::class)->find($id);
        if (!$recipeProduct) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($recipeProduct);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
