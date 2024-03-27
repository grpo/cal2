<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\EntityUpdaterService;
use App\Service\ValidatorViolationAggregator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/products', name: 'app_products_')]
class ProductsController extends AbstractApiController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        if (!$products) {
            return $this->json([]);
        }
        $payload = json_decode($this->serializer->serialize($products, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        string $id,
    ): JsonResponse {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json([]);
        }
        $payload = json_decode($this->serializer->serialize($product, 'json'));

        return new JsonResponse($payload);
    }


    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        ValidatorViolationAggregator $validatorViolationAggregator,
    ): JsonResponse {
        $validJson = $this->jsonValidator->validate($request->getContent());
        $product = $this->serializer->deserialize($validJson, Product::class, 'json');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $productPayload = json_decode($this->serializer->serialize($product, 'json'));

        return new JsonResponse($productPayload);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        string $id,
    ): JsonResponse {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse('', Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(
        string $id,
        Request $request,
        ValidatorInterface $validator,
        EntityUpdaterService $entityUpdaterService,
        ValidatorViolationAggregator $validatorViolationAggregator,
    ): JsonResponse {
        $validJson = $this->jsonValidator->validate($request->getContent());
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse('', Response::HTTP_BAD_REQUEST);
        }
        $requestProduct = $this->serializer->deserialize($validJson, Product::class, 'json');
        $updatedProduct = $entityUpdaterService->update($product, $requestProduct);

        $errors = $validator->validate($updatedProduct);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($updatedProduct);
        $this->entityManager->flush();

        $payload = json_decode($this->serializer->serialize($updatedProduct, 'json'));

        return new JsonResponse($payload);
    }
}
