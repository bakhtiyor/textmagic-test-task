<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\UserQuiz;
use App\Entity\UserQuizAnswer;
use App\Entity\UserQuizResult;
use App\Enum\UserQuizStatus;
use App\Repository\AnswerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

class SaveUserAnswersService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AnswerRepository $answerRepository
    ) {
    }

    /**
     * @throws ORMException
     */
    public function execute(UserQuiz $userQuiz, array $answers): void
    {
        if (!in_array($userQuiz->getStatus(), [UserQuizStatus::QUEUED, UserQuizStatus::STARTED], true)) {
            throw new RuntimeException('User quiz is not in progress');
        }
        $needToFlush = count($answers) > 0;
        foreach ($answers as $questionId => $answerIds) {
            $question = $this->entityManager->getReference(Question::class, new Uuid($questionId));
            if ($question === null) {
                throw new RuntimeException('Question not found');
            }
            $questionCorrectAnswers = $this->answerRepository->getQuestionCorrectAnswers($question);
            foreach ($answerIds as $answerId) {
                $answer = $this->entityManager->getReference(Answer::class, new Uuid($answerId));
                if ($answer === null) {
                    throw new RuntimeException('Answer not found');
                }
                $this->createUserQuizAnswer($userQuiz, $question, $answer);
            }
            $userCorrectAnswerCount = 0;
            foreach ($questionCorrectAnswers as $questionCorrectAnswer) {
                if (in_array($questionCorrectAnswer->getId(), $answerIds, true)) {
                    $userCorrectAnswerCount++;
                }
            }

            $this->createUserQuizResult(
                $userQuiz,
                $question,
                count($answerIds) === count($questionCorrectAnswers)
                && $userCorrectAnswerCount === count($questionCorrectAnswers)
            );
        }
        if ($needToFlush) {
            $userQuiz->setStatus(UserQuizStatus::FINISHED);
            $this->entityManager->flush();
        }
    }

    private function createUserQuizAnswer(UserQuiz $userQuiz, Question $question, Answer $answer): void
    {
        $userQuizAnswer = new UserQuizAnswer();
        $userQuizAnswer->setUserQuiz($userQuiz)
            ->setQuestion($question)
            ->setAnswer($answer)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->entityManager->persist($userQuizAnswer);
    }

    private function createUserQuizResult(UserQuiz $userQuiz, Question $question, bool $correct): void
    {
        $userQuizResult = new UserQuizResult();
        $userQuizResult->setUserQuiz($userQuiz)
            ->setQuestion($question)
            ->setCorrect($correct)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->entityManager->persist($userQuizResult);
    }
}
