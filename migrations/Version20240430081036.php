<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240430081036 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_quiz_answer (
                id UUID NOT NULL, 
                user_quiz_id UUID NOT NULL,
                question_id UUID NOT NULL, 
                answer_id UUID NOT NULL, 
                correct BOOLEAN NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
          )'
        );
        $this->addSql('CREATE INDEX IDX_9E8273E9DD31CF7F ON user_quiz_answer (user_quiz_id)');
        $this->addSql('CREATE INDEX IDX_9E8273E91E27F6BF ON user_quiz_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_9E8273E9AA334807 ON user_quiz_answer (answer_id)');
        $this->addSql('COMMENT ON COLUMN user_quiz_answer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz_answer.user_quiz_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz_answer.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz_answer.answer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('
            ALTER TABLE user_quiz_answer 
                ADD CONSTRAINT FK_9E8273E9DD31CF7F FOREIGN KEY (user_quiz_id)
                REFERENCES user_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE user_quiz_answer 
                ADD CONSTRAINT FK_9E8273E91E27F6BF FOREIGN KEY (question_id)
                REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE user_quiz_answer 
                ADD CONSTRAINT FK_9E8273E9AA334807 FOREIGN KEY (answer_id) 
                REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_quiz_answer DROP CONSTRAINT FK_9E8273E9DD31CF7F');
        $this->addSql('ALTER TABLE user_quiz_answer DROP CONSTRAINT FK_9E8273E91E27F6BF');
        $this->addSql('ALTER TABLE user_quiz_answer DROP CONSTRAINT FK_9E8273E9AA334807');
        $this->addSql('DROP TABLE user_quiz_answer');
    }
}
