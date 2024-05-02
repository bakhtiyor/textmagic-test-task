<?php

namespace App\Entity;

use App\Enum\UserQuizStatus;
use App\Repository\UserQuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserQuizRepository::class)]
class UserQuiz
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'userQuizzes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Quiz $quiz;

    #[ORM\Column(length: 20)]
    private UserQuizStatus $status;

    /**
     * @var Collection<int, UserQuizAnswer>
     */
    #[ORM\OneToMany(targetEntity: UserQuizAnswer::class, mappedBy: 'userQuiz', orphanRemoval: true)]
    private Collection $answers;

    /**
     * @var Collection<int, UserQuizResult>
     */
    #[ORM\OneToMany(targetEntity: UserQuizResult::class, mappedBy: 'userQuiz', orphanRemoval: true)]
    private Collection $results;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getStatus(): UserQuizStatus
    {
        return $this->status;
    }

    public function setStatus(UserQuizStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, UserQuizAnswer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(UserQuizAnswer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setUserQuiz($this);
        }

        return $this;
    }

    public function removeAnswer(UserQuizAnswer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getUserQuiz() === $this) {
                $answer->setUserQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserQuizResult>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(UserQuizResult $result): static
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setUserQuiz($this);
        }

        return $this;
    }

    public function removeResult(UserQuizResult $result): static
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getUserQuiz() === $this) {
                $result->setUserQuiz(null);
            }
        }

        return $this;
    }

    public function correctQuestions(): Collection
    {
        $criteria = (new Criteria())->andWhere(Criteria::expr()->eq('correct', true));
        return $this->results->matching($criteria);
    }

    public function incorrectQuestions(): Collection
    {
        $criteria = (new Criteria())->andWhere(Criteria::expr()->eq('correct', false));
        return $this->results->matching($criteria);
    }

    public function unansweredQuestions(): Collection
    {
        $allQuestions = $this->quiz->getQuestions();
        $answeredQuestions = $this->results->map(fn(UserQuizResult $result) => $result->getQuestion());
        return $allQuestions->filter(fn(Question $question) => !$answeredQuestions->contains($question));
    }
}
