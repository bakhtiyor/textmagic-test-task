<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\UserQuiz;
use App\Entity\UserQuizAnswer;
use App\Entity\UserQuizResult;
use App\Enum\UserQuizStatus;
use App\Exception\UserQuizException;
use App\Repository\AnswerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
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
     * @throws UserQuizException
     */
    public function execute(UserQuiz $userQuiz, array $userAnswers): void
    {
        if (!UserQuizStatus::validToBeTaken($userQuiz->getStatus())) {
            throw new UserQuizException('User quiz is not started or queued yet');
        }
        $needToFlush = count($userAnswers) > 0;
        foreach ($userAnswers as $userAnswerQuestionId => $userAnswerIds) {
            $question = $this->entityManager->getReference(Question::class, new Uuid($userAnswerQuestionId));
            if (!$question) {
                throw new UserQuizException('Question not found');
            }
            // save user answers
            foreach ($userAnswerIds as $userAnswerId) {
                $this->createUserQuizAnswer($userQuiz, $question, $userAnswerId);
            }

            // check user answers with correct answers from db
            $questionCorrectAnswerIds = $this->answerRepository->getQuestionCorrectAnswerIds($question);
            $isCorrect = true;
            foreach ($userAnswerIds as $userAnswerId) {
                if (!in_array($userAnswerId, $questionCorrectAnswerIds, true)) {
                    $isCorrect = false;
                    break;
                }
            }

            // save user quiz results
            $this->createUserQuizResult($userQuiz, $question, $isCorrect);
        }
        if ($needToFlush) {
            $userQuiz->setStatus(UserQuizStatus::FINISHED);
            $this->entityManager->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws UserQuizException
     */
    private function createUserQuizAnswer(UserQuiz $userQuiz, Question $question, string $userAnswerId): void
    {
        $answer = $this->entityManager->getReference(Answer::class, new Uuid($userAnswerId));
        if ($answer === null) {
            throw new UserQuizException('Answer not found');
        }

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
