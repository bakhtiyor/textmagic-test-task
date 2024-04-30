<?php

namespace App\Repository;

use App\Entity\UserQuizAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserQuizAnswer>
 *
 * @method UserQuizAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserQuizAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserQuizAnswer[]    findAll()
 * @method UserQuizAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuizAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserQuizAnswer::class);
    }
}
