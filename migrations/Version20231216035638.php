<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231216035638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE contact_emails_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE contact_email_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact_email (id INT NOT NULL, contact_id INT NOT NULL, email VARCHAR(255) NOT NULL, notes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAB86C7BE7A1254A ON contact_email (contact_id)');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, sender_type INT NOT NULL, inquiry_id INT NOT NULL, mail_id INT DEFAULT NULL, phone_id INT DEFAULT NULL, file VARCHAR(511) NOT NULL, message_id VARCHAR(511) NOT NULL, reference_id VARCHAR(511) DEFAULT NULL, subject VARCHAR(1023) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FA7AD6D71 ON message (inquiry_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FC8776F01 ON message (mail_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F3B7323CB ON message (phone_id)');
        $this->addSql('ALTER TABLE contact_email ADD CONSTRAINT FK_CAB86C7BE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC8776F01 FOREIGN KEY (mail_id) REFERENCES contact_email (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3B7323CB FOREIGN KEY (phone_id) REFERENCES contact_phone (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE contact_emails');
        $this->addSql('ALTER TABLE category ALTER parent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE category RENAME COLUMN group_id TO group__id');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1E5D32D49 FOREIGN KEY (group__id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_64C19C1E5D32D49 ON category (group__id)');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('ALTER TABLE contact_phone ADD CONSTRAINT FK_696587D2E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_696587D2E7A1254A ON contact_phone (contact_id)');
        $this->addSql('ALTER TABLE "group" ALTER parent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5727ACA70 FOREIGN KEY (parent_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C5727ACA70 ON "group" (parent_id)');
        $this->addSql('ALTER TABLE inquiry ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F0AE80F5DF FOREIGN KEY (department_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F03414710B FOREIGN KEY (agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A3903F012469DE2 ON inquiry (category_id)');
        $this->addSql('CREATE INDEX IDX_5A3903F0AE80F5DF ON inquiry (department_id)');
        $this->addSql('CREATE INDEX IDX_5A3903F03414710B ON inquiry (agent_id)');
        $this->addSql('ALTER TABLE mail_account RENAME COLUMN group_id TO group__id');
        $this->addSql('ALTER TABLE mail_account ADD CONSTRAINT FK_A78BD7CBE5D32D49 FOREIGN KEY (group__id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A78BD7CBE5D32D49 ON mail_account (group__id)');
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_group ADD user__id INT NOT NULL');
        $this->addSql('ALTER TABLE user_group ADD group__id INT NOT NULL');
        $this->addSql('ALTER TABLE user_group DROP user_id');
        $this->addSql('ALTER TABLE user_group DROP group_id');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D8D57A4BB FOREIGN KEY (user__id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DE5D32D49 FOREIGN KEY (group__id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8F02BF9D8D57A4BB ON user_group (user__id)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DE5D32D49 ON user_group (group__id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contact_email_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE contact_emails_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact_emails (id INT NOT NULL, contact_id INT NOT NULL, email VARCHAR(255) NOT NULL, notes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE contact_email DROP CONSTRAINT FK_CAB86C7BE7A1254A');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FA7AD6D71');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FC8776F01');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F3B7323CB');
        $this->addSql('DROP TABLE contact_email');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE mail_account DROP CONSTRAINT FK_A78BD7CBE5D32D49');
        $this->addSql('DROP INDEX IDX_A78BD7CBE5D32D49');
        $this->addSql('ALTER TABLE mail_account RENAME COLUMN group__id TO group_id');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5727ACA70');
        $this->addSql('DROP INDEX IDX_6DC044C5727ACA70');
        $this->addSql('ALTER TABLE "group" ALTER parent_id SET NOT NULL');
        $this->addSql('ALTER TABLE inquiry DROP CONSTRAINT FK_5A3903F012469DE2');
        $this->addSql('ALTER TABLE inquiry DROP CONSTRAINT FK_5A3903F0AE80F5DF');
        $this->addSql('ALTER TABLE inquiry DROP CONSTRAINT FK_5A3903F03414710B');
        $this->addSql('DROP INDEX IDX_5A3903F012469DE2');
        $this->addSql('DROP INDEX IDX_5A3903F0AE80F5DF');
        $this->addSql('DROP INDEX IDX_5A3903F03414710B');
        $this->addSql('ALTER TABLE inquiry DROP category_id');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1E5D32D49');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1727ACA70');
        $this->addSql('DROP INDEX IDX_64C19C1E5D32D49');
        $this->addSql('DROP INDEX IDX_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category ALTER parent_id SET NOT NULL');
        $this->addSql('ALTER TABLE category RENAME COLUMN group__id TO group_id');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9D8D57A4BB');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DE5D32D49');
        $this->addSql('DROP INDEX IDX_8F02BF9D8D57A4BB');
        $this->addSql('DROP INDEX IDX_8F02BF9DE5D32D49');
        $this->addSql('ALTER TABLE user_group ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_group ADD group_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_group DROP user__id');
        $this->addSql('ALTER TABLE user_group DROP group__id');
        $this->addSql('ALTER TABLE "user" DROP name');
        $this->addSql('ALTER TABLE contact_phone DROP CONSTRAINT FK_696587D2E7A1254A');
        $this->addSql('DROP INDEX IDX_696587D2E7A1254A');
    }
}
