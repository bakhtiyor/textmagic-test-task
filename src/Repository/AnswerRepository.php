<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * Get all answers for a question.
     * @return Answer[]
     */
    public function getQuestionCorrectAnswers(Question $question): array
    {
        return $this->createQueryBuilder('answer')
            ->innerJoin('answer.question', 'question')
            ->andWhere('question = :question')
            ->setParameter('question', $question)
            ->andWhere('answer.correct = true')
            ->getQuery()
            ->getResult();
    }
}
