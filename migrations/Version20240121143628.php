<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121143628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE users_groups_id_seq CASCADE');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0a76ed395');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0fe54d947');
        $this->addSql('DROP TABLE users_groups');
        $this->addSql('ALTER TABLE contact_item ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mail_account ALTER pop_server TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER smtp_server TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER username TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER password TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE users_groups_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE users_groups (id INT NOT NULL, user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_ff8ab7e0fe54d947 ON users_groups (group_id)');
        $this->addSql('CREATE INDEX idx_ff8ab7e0a76ed395 ON users_groups (user_id)');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mail_account ALTER pop_server TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER smtp_server TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER username TYPE TEXT');
        $this->addSql('ALTER TABLE mail_account ALTER password TYPE TEXT');
        $this->addSql('ALTER TABLE contact_item DROP type');
    }
}
