<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getQuizQuestionsAndAnswerOptions(Quiz $getQuiz): Quiz
    {
        return $this->createQueryBuilder('quiz')
            ->innerJoin('quiz.questions', 'question')
            ->innerJoin('question.answers', 'answer')
            ->select('quiz', 'question', 'answer')
            ->andWhere('quiz = :quiz')
            ->setParameter('quiz', $getQuiz)
            ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
