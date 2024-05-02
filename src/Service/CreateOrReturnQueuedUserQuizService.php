<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\User;
use App\Entity\UserQuiz;
use App\Enum\UserQuizStatus;
use App\Repository\UserQuizRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CreateOrReturnQueuedUserQuizService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserQuizRepository $userQuizRepository
    ) {
    }

    public function execute(Quiz $quiz, User $user): UserQuiz
    {
        if (
            $existedUserQuiz = $this->userQuizRepository
                ->findOneBy(['user' => $user, 'quiz' => $quiz, 'status' => UserQuizStatus::QUEUED])
        ) {
            return $existedUserQuiz;
        }

        $userQuiz = new UserQuiz();
        $userQuiz->setQuiz($quiz)
            ->setUser($user)
            ->setStatus(UserQuizStatus::QUEUED)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->entityManager->persist($userQuiz);
        $this->entityManager->flush();

        return $userQuiz;
    }
}
