<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240430073230 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_quiz (
                id UUID NOT NULL, 
                user_id UUID NOT NULL, 
                quiz_id UUID NOT NULL, 
                status VARCHAR(20) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
           )'
        );
        $this->addSql('CREATE INDEX IDX_DE93B65BA76ED395 ON user_quiz (user_id)');
        $this->addSql('CREATE INDEX IDX_DE93B65B853CD175 ON user_quiz (quiz_id)');
        $this->addSql('COMMENT ON COLUMN user_quiz.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_quiz.quiz_id IS \'(DC2Type:uuid)\'');
        $this->addSql('
                ALTER TABLE user_quiz 
                    ADD CONSTRAINT FK_DE93B65BA76ED395 FOREIGN KEY (user_id) 
                    REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
                ALTER TABLE user_quiz 
                    ADD CONSTRAINT FK_DE93B65B853CD175 FOREIGN KEY (quiz_id) 
                    REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_quiz DROP CONSTRAINT FK_DE93B65BA76ED395');
        $this->addSql('ALTER TABLE user_quiz DROP CONSTRAINT FK_DE93B65B853CD175');
        $this->addSql('DROP TABLE user_quiz');
    }
}
