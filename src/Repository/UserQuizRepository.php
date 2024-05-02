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

    public function getUserQuizQuestionsWithAnswersByStatus(UserQuiz $userQuiz, bool $correct): array
    {
        $qb = $this->createQueryBuilder('userQuiz')
            ->innerJoin('userQuiz.results', 'userQuizResult')
            ->innerJoin('userQuiz.answers', 'userQuizAnswer')
            ->innerJoin('userQuizResult.question', 'question')
            ->innerJoin('userQuizAnswer.answer', 'answer')
            ->select(
                'question.title as questionTitle',
                'answer.title as answerTitle',
                'userQuizResult.correct as correct'
            )
            ->andWhere('userQuiz = :userQuiz')
            ->andWhere('userQuizResult.correct = :correct')
            ->setParameter('userQuiz', $userQuiz)
            ->setParameter('correct', $correct)
            ->getQuery();
        $sql = $qb->getSQL();
        return $qb->getResult();
    }
}
