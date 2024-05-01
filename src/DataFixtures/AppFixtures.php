<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create a new user
        $this->createUser($manager);

        // Create a new quiz
        $quiz = new Quiz();
        $quiz->setTitle('Quiz 1');
        $quiz->setCreatedAt(new DateTime());
        $quiz->setUpdatedAt(new DateTime());
        $manager->persist($quiz);

        // Create questions and answers
        $question = $this->createQuestion('1 + 1 = ?', $quiz, 1, $manager);
        $this->createAnswer('3', $question, false, $manager);
        $this->createAnswer('2', $question, true, $manager);
        $this->createAnswer('0', $question, false, $manager);

        $question = $this->createQuestion('2 + 2 = ?', $quiz, 2, $manager);
        $this->createAnswer('4', $question, true, $manager);
        $this->createAnswer('3 + 1', $question, true, $manager);
        $this->createAnswer('10', $question, false, $manager);

        $question = $this->createQuestion('3 + 3 = ?', $quiz, 3, $manager);
        $this->createAnswer('1 + 5', $question, true, $manager);
        $this->createAnswer('1', $question, false, $manager);
        $this->createAnswer('6', $question, true, $manager);
        $this->createAnswer('2 + 4', $question, true, $manager);

        $question = $this->createQuestion('4 + 4 = ?', $quiz, 4, $manager);
        $this->createAnswer('8', $question, true, $manager);
        $this->createAnswer('4', $question, false, $manager);
        $this->createAnswer('0', $question, false, $manager);
        $this->createAnswer('0 + 8', $question, true, $manager);

        $question = $this->createQuestion('5 + 5 = ?', $quiz, 5, $manager);
        $this->createAnswer('6', $question, false, $manager);
        $this->createAnswer('18', $question, false, $manager);
        $this->createAnswer('10', $question, true, $manager);
        $this->createAnswer('9', $question, false, $manager);
        $this->createAnswer('0', $question, false, $manager);

        $question = $this->createQuestion('6 + 6 = ?', $quiz, 6, $manager);
        $this->createAnswer('3', $question, false, $manager);
        $this->createAnswer('9', $question, false, $manager);
        $this->createAnswer('0', $question, false, $manager);
        $this->createAnswer('12', $question, true, $manager);
        $this->createAnswer('5 + 7', $question, true, $manager);

        $question = $this->createQuestion('7 + 7 = ?', $quiz, 7, $manager);
        $this->createAnswer('5', $question, false, $manager);
        $this->createAnswer('14', $question, true, $manager);

        $question = $this->createQuestion('8 + 8 = ?', $quiz, 8, $manager);
        $this->createAnswer('16', $question, true, $manager);
        $this->createAnswer('12', $question, false, $manager);
        $this->createAnswer('9', $question, false, $manager);
        $this->createAnswer('5', $question, false, $manager);

        $question = $this->createQuestion('9 + 9 = ?', $quiz, 9, $manager);
        $this->createAnswer('18', $question, true, $manager);
        $this->createAnswer('9', $question, false, $manager);
        $this->createAnswer('17 + 1', $question, true, $manager);
        $this->createAnswer('2 + 16', $question, true, $manager);

        $question = $this->createQuestion('10 + 10 = ?', $quiz, 10, $manager);
        $this->createAnswer('0', $question, false, $manager);
        $this->createAnswer('2', $question, false, $manager);
        $this->createAnswer('8', $question, false, $manager);
        $this->createAnswer('20', $question, true, $manager);

        $manager->flush();
    }

    private function createQuestion(string $title, Quiz $quiz, int $position, ObjectManager $manager): Question
    {
        $question = new Question();
        $question->setTitle($title);
        $question->setQuiz($quiz);
        $question->setPosition($position);
        $question->setCreatedAt(new DateTime());
        $question->setUpdatedAt(new DateTime());
        $manager->persist($question);

        return $question;
    }

    private function createAnswer(string $title, Question $question, bool $isCorrect, ObjectManager $manager): Answer
    {
        $answer = new Answer();
        $answer->setTitle($title);
        $answer->setQuestion($question);
        $answer->setCorrect($isCorrect);
        $answer->setCreatedAt(new DateTime());
        $answer->setUpdatedAt(new DateTime());
        $manager->persist($answer);

        return $answer;
    }

    private function createUser(ObjectManager $manager): User
    {
        $user = new User();
        $user->setEmail('i@bakhtiyor.tj');
        $user->setPassword($this->passwordHasher->hashPassword($user, '123456'));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        return $user;
    }
}
