<?php

namespace App\Repository;

use App\Entity\RecipeProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeProducts>
 *
 * @method RecipeProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeProducts[]    findAll()
 * @method RecipeProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeProducts::class);
    }
}
