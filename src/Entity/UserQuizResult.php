<?php

namespace App\Entity;

use App\Repository\UserQuizResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserQuizResultRepository::class)]
class UserQuizResult
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private UserQuiz $userQuiz;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Question $question;

    #[ORM\Column]
    private bool $correct;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUserQuiz(): UserQuiz
    {
        return $this->userQuiz;
    }

    public function setUserQuiz(UserQuiz $userQuiz): static
    {
        $this->userQuiz = $userQuiz;

        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): static
    {
        $this->correct = $correct;

        return $this;
    }
}
