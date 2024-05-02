<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\User;
use App\Repository\QuizRepository;
use App\Repository\UserQuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserQuizController extends AbstractController
{
    private CONST LIMIT = 30;
    public function __construct(
        private readonly QuizRepository $quizRepository,
        private readonly UserQuizRepository $userQuizRepository
    ) {
    }

    #[Route('/user-quiz', name: 'user-quiz-index')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $quizzes = $this->quizRepository->findAll();
        $userQuizzes = $this->userQuizRepository->getUserQuizzes($user, self::LIMIT);

        return $this->render('user-quiz/index.html.twig', [
            'quizzes' => $quizzes,
            'userQuizzes' => $userQuizzes
        ]);
    }

    #[Route('/user-quiz/{quiz}/create', name: 'user-quiz-create')]
    public function createUserQuiz(Quiz $quiz): Response
    {
        return $this->render('user-quiz/index.html.twig');
    }
}
