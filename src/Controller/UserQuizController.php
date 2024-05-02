<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\User;
use App\Entity\UserQuiz;
use App\Repository\QuizRepository;
use App\Repository\UserQuizRepository;
use App\Service\CreateOrReturnQueuedUserQuizService;
use App\Service\SaveUserAnswersService;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/take/quiz/{quiz}', name: 'take-quiz')]
    public function takeQuiz(
        Quiz $quiz,
        CreateOrReturnQueuedUserQuizService $createOrReturnQueuedUserQuizService
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $userQuiz = $createOrReturnQueuedUserQuizService->execute($quiz, $user);
        $quizWithQuestions = $this->quizRepository->getQuizQuestionsAndAnswerOptions($userQuiz->getQuiz());
        return $this->render(
            'user-quiz/take.html.twig', [
                'userQuiz' => $userQuiz,
                'quizWithQuestions' => $quizWithQuestions
        ]);
    }

    /**
     * @throws ORMException
     */
    #[Route('/submit-quiz/{userQuiz}', name: 'submit-quiz', methods: ['POST'])]
    public function submitQuiz(
        UserQuiz $userQuiz,
        Request $request,
        SaveUserAnswersService $saveUserAnswersService
    ): Response {
        $answers = $request->request->all('answers');
        $saveUserAnswersService->execute($userQuiz, $answers);

        return $this->redirectToRoute('user-quiz-index');
    }
}
