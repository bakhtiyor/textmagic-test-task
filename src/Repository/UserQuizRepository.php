<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserQuiz>
 *
 * @method UserQuiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserQuiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserQuiz[]    findAll()
 * @method UserQuiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserQuiz::class);
    }

    public function getUserQuizzes(User $user, int $limit = 10): array
    {
        return $this->createQueryBuilder('userQuiz')
            ->innerJoin('userQuiz.quiz', 'quiz')
            ->innerJoin('userQuiz.user', 'user')
            ->select('userQuiz', 'quiz')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
            ->setMaxResults($limit)
            ->orderBy('userQuiz.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
