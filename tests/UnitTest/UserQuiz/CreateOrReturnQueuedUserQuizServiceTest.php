<?php

namespace App\Tests\UnitTest\UserQuiz;

use App\Entity\Quiz;
use App\Entity\User;
use App\Entity\UserQuiz;
use App\Enum\UserQuizStatus;
use App\Repository\UserQuizRepository;
use App\Service\CreateOrReturnQueuedUserQuizService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateOrReturnQueuedUserQuizServiceTest extends TestCase
{
    private $entityManager;
    private $userQuizRepository;
    private $service;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->userQuizRepository = $this->createMock(UserQuizRepository::class);
        $this->service = new CreateOrReturnQueuedUserQuizService($this->entityManager, $this->userQuizRepository);
    }

    /**
     * Test when user does not have queued quiz
     * @return void
     */
    public function testWhenUserDoesNotHaveQueuedQuiz(): void
    {
        $quiz = new Quiz();
        $user = new User();

        $this->userQuizRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['user' => $user, 'quiz' => $quiz, 'status' => UserQuizStatus::QUEUED])
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(UserQuiz::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $userQuiz = $this->service->execute($quiz, $user);

        $this->assertEquals($quiz, $userQuiz->getQuiz());
        $this->assertEquals($user, $userQuiz->getUser());
        $this->assertEquals(UserQuizStatus::QUEUED, $userQuiz->getStatus());
    }

    /**
     * Test when user already has a queued quiz
     * @return void
     */
    public function testWhenUserAlreadyHasQueuedQuiz(): void
    {
        $quiz = new Quiz();
        $user = new User();
        $userQuiz = new UserQuiz();
        $userQuiz->setQuiz($quiz);
        $userQuiz->setUser($user);
        $userQuiz->setStatus(UserQuizStatus::QUEUED);

        $this->userQuizRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['user' => $user, 'quiz' => $quiz, 'status' => UserQuizStatus::QUEUED])
            ->willReturn($userQuiz);

        $this->entityManager->expects($this->never())
            ->method('persist');

        $this->entityManager->expects($this->never())
            ->method('flush');

        $returnedUserQuiz = $this->service->execute($quiz, $user);

        $this->assertSame($userQuiz, $returnedUserQuiz);
    }
}
