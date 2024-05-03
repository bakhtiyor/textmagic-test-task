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
     * @param Question $question
     * @return array
     */
    public function getQuestionCorrectAnswerIds(Question $question): array
    {
        $answerIds = $this->createQueryBuilder('answer')
            ->select('answer.id')
            ->innerJoin('answer.question', 'question')
            ->andWhere('question = :question')
            ->setParameter('question', $question)
            ->andWhere('answer.correct = true')
            ->getQuery()
            ->getResult();

        return array_map(static function (array $answerArray) {
            return $answerArray['id']->toRfc4122();
        }, $answerIds);
    }
}
