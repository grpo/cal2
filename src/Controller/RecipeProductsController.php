<?php

namespace App\Controller;

use App\Entity\RecipeProduct;
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

#[Route('/recipe-products', name: 'app_recipe-products_')]
class RecipeProductsController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $recipeProducts = $entityManager->getRepository(RecipeProduct::class)->findAll();
        if (!$recipeProducts) {
            return new JsonResponse();
        }
        $payload = json_decode($serializer->serialize($recipeProducts, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        string $id,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Request $request
    ): JsonResponse {
        $recipeProduct = $entityManager->getRepository(RecipeProduct::class)->find($id);
        if (!$recipeProduct) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $payload = json_decode($serializer->serialize($recipeProduct, 'json'));

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
        $validJson = $jsonValidator->validate($request->getContent());
        $recipeProduct = $serializer->deserialize($validJson, RecipeProduct::class, 'json');
        $errors = $validator->validate($recipeProduct);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($recipeProduct);
        $entityManager->flush();
        $payload = json_decode($serializer->serialize($recipeProduct, 'json'));

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
        $recipeProduct = $entityManager->getRepository(RecipeProduct::class)->find($id);
        if (!$recipeProduct) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $updatedRecipeProduct = $serializer->deserialize($requestContent, RecipeProduct::class, 'json');
        $updatedRecipeProduct = $entityUpdaterService->update($recipeProduct, $updatedRecipeProduct);

        $errors = $validator->validate($updatedRecipeProduct);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($recipeProduct);
        $entityManager->flush();
        $payload = json_decode($serializer->serialize($recipeProduct, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        string $id,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $recipeProduct = $entityManager->getRepository(RecipeProduct::class)->find($id);
        if (!$recipeProduct) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($recipeProduct);
        $entityManager->flush();

        return new JsonResponse();
    }
}
