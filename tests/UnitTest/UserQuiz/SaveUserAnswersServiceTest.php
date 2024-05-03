<?php

namespace App\Tests\UnitTest\UserQuiz;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\UserQuiz;
use App\Enum\UserQuizStatus;
use App\Exception\UserQuizException;
use App\Repository\AnswerRepository;
use App\Service\SaveUserAnswersService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SaveUserAnswersServiceTest extends TestCase
{
    private $entityManager;
    private $answerRepository;
    private $service;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->answerRepository = $this->createMock(AnswerRepository::class);
        $this->service = new SaveUserAnswersService($this->entityManager, $this->answerRepository);
    }

    public function testExecuteWithValidUserQuizAndAnswers(): void
    {
        $userQuiz = new UserQuiz();
        $userQuiz->setStatus(UserQuizStatus::STARTED);
        $userAnswers = [Uuid::v7()->toRfc4122() => [Uuid::v7()->toRfc4122()]];

        $this->entityManager->expects($this->any())
            ->method('getReference')
            ->willReturnOnConsecutiveCalls(new Question(), new Answer());

        $this->answerRepository->expects($this->once())
            ->method('getQuestionCorrectAnswerIds')
            ->willReturn(['answerId']);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->execute($userQuiz, $userAnswers);

        $this->assertEquals(UserQuizStatus::FINISHED, $userQuiz->getStatus());
    }

    public function testExecuteWithInvalidUserQuizStatus()
    {
        $this->expectException(UserQuizException::class);
        $this->expectExceptionMessage('User quiz is not started or queued yet');

        $userQuiz = new UserQuiz();
        $userQuiz->setStatus(UserQuizStatus::FINISHED);
        $userAnswers = [Uuid::v7()->toRfc4122() => [Uuid::v7()->toRfc4122()]];

        $this->service->execute($userQuiz, $userAnswers);
    }

    public function testExecuteWithNonExistentQuestion(): void
    {
        $this->expectException(UserQuizException::class);
        $this->expectExceptionMessage('Question not found');

        $userQuiz = new UserQuiz();
        $userQuiz->setStatus(UserQuizStatus::STARTED);
        $userAnswers = [Uuid::v7()->toRfc4122() => [Uuid::v7()->toRfc4122()]];

        $this->entityManager->expects($this->once())
            ->method('getReference')
            ->willReturn(null);

        $this->service->execute($userQuiz, $userAnswers);
    }

    public function testExecuteWithNonExistentAnswer(): void
    {
        $this->expectException(UserQuizException::class);
        $this->expectExceptionMessage('Answer not found');

        $userQuiz = new UserQuiz();
        $userQuiz->setStatus(UserQuizStatus::STARTED);
        $userAnswers = [Uuid::v4()->toRfc4122() => [Uuid::v4()->toRfc4122()]];

        $this->entityManager->expects($this->any())
            ->method('getReference')
            ->willReturnOnConsecutiveCalls(new Question(), null);

        $this->service->execute($userQuiz, $userAnswers);
    }
}
