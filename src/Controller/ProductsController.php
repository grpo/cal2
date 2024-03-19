<?php

namespace App\Controller;

use App\Dto\ProductDto;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products', name: 'app_products_')]
class ProductsController extends AbstractController
{
    public function __construct
    (
        private SerializerInterface     $serializer,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index
    (
        EntityManagerInterface  $entityManager,
        SerializerInterface     $serializer,
    ): JsonResponse {
        $products = $entityManager->getRepository(Product::class)->findAll();
            if (!$products) {
                return $this->json([]);
            }
        $payload = json_decode($serializer->serialize($products, 'json'));

        return new JsonResponse($payload);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create
    (
        Request $request,
    ): JsonResponse {
        $content = $request->getContent();
        $productDto = $this->serializer->deserialize($content, ProductDto::class, 'json');


        return $this->json('');
    }
}
