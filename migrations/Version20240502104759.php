<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240502104759 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_quiz_result (
                id UUID NOT NULL, 
                user_quiz_id UUID NOT NULL, 
                question_id UUID NOT NULL, 
                correct BOOLEAN NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )'
        );
        $this->addSql('CREATE INDEX IDX_5735F8DFDD31CF7F ON user_quiz_result (user_quiz_id)');
        $this->addSql('CREATE INDEX IDX_5735F8DF1E27F6BF ON user_quiz_result (question_id)');
        $this->addSql('COMMENT ON COLUMN user_quiz_result.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz_result.user_quiz_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz_result.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('
            ALTER TABLE user_quiz_result 
                ADD CONSTRAINT FK_5735F8DFDD31CF7F FOREIGN KEY (user_quiz_id) 
                REFERENCES user_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE user_quiz_result 
                ADD CONSTRAINT FK_5735F8DF1E27F6BF FOREIGN KEY (question_id) 
                REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('ALTER TABLE user_quiz_answer DROP correct');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_quiz_result DROP CONSTRAINT FK_5735F8DFDD31CF7F');
        $this->addSql('ALTER TABLE user_quiz_result DROP CONSTRAINT FK_5735F8DF1E27F6BF');
        $this->addSql('DROP TABLE user_quiz_result');
        $this->addSql('ALTER TABLE user_quiz_answer ADD correct BOOLEAN NOT NULL');
    }
}
