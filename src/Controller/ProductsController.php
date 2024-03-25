<?php

namespace App\Controller;

use App\Entity\Product;
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

#[Route('/products', name: 'app_products_')]
class ProductsController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ): JsonResponse {
        $products = $entityManager->getRepository(Product::class)->findAll();
        if (!$products) {
            return $this->json([]);
        }
        $payload = json_decode($serializer->serialize($products, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        string $id,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ): JsonResponse {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json([]);
        }
        $payload = json_decode($serializer->serialize($product, 'json'));

        return new JsonResponse($payload);
    }


    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        ValidatorViolationAggregator $validatorViolationAggregator,
        JsonValidator $jsonValidator
    ): JsonResponse {
        $requestContent = $jsonValidator->validate($request->getContent());
        $product = $this->serializer->deserialize($requestContent, Product::class, 'json');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        $productPayload = json_decode($this->serializer->serialize($product, 'json'));

        return new JsonResponse($productPayload);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        string $id,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse('', Response::HTTP_BAD_REQUEST);
        }
        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(
        string $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        EntityUpdaterService $entityUpdaterService,
        ValidatorViolationAggregator $validatorViolationAggregator,
        JsonValidator $jsonValidator
    ): JsonResponse {
        $requestContent = $jsonValidator->validate($request->getContent());
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse('', Response::HTTP_BAD_REQUEST);
        }
        $requestProduct = $this->serializer->deserialize($requestContent, Product::class, 'json');
        $updatedProduct = $entityUpdaterService->update($product, $requestProduct);

        $errors = $validator->validate($updatedProduct);
        if (count($errors) > 0) {
            return new JsonResponse($validatorViolationAggregator->getViolations($errors), Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($updatedProduct);
        $entityManager->flush();

        $payload = json_decode($this->serializer->serialize($updatedProduct, 'json'));

        return new JsonResponse($payload);
    }
}
