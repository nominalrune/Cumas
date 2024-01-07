<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103121134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mail_account ADD pop_server TEXT NOT NULL');
        $this->addSql('ALTER TABLE mail_account ADD smtp_server TEXT NOT NULL');
        $this->addSql('ALTER TABLE mail_account ADD smtp_port INT NOT NULL');
        $this->addSql('ALTER TABLE mail_account DROP host');
        $this->addSql('ALTER TABLE mail_account ALTER username TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER password TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account RENAME COLUMN port TO pop_port');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mail_account ADD host TEXT NOT NULL');
        $this->addSql('ALTER TABLE mail_account ADD port INT NOT NULL');
        $this->addSql('ALTER TABLE mail_account DROP pop_server');
        $this->addSql('ALTER TABLE mail_account DROP pop_port');
        $this->addSql('ALTER TABLE mail_account DROP smtp_server');
        $this->addSql('ALTER TABLE mail_account DROP smtp_port');
        $this->addSql('ALTER TABLE mail_account ALTER username TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER password TYPE TEXT');
    }
}
