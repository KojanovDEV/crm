<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319162005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_owner DROP FOREIGN KEY FK_522DA7F3979B1AD6');
        $this->addSql('ALTER TABLE email_owner DROP FOREIGN KEY FK_522DA7F3A76ED395');
        $this->addSql('DROP INDEX IDX_522DA7F3A76ED395 ON email_owner');
        $this->addSql('DROP INDEX IDX_522DA7F3979B1AD6 ON email_owner');
        $this->addSql('ALTER TABLE email_owner DROP user_id, DROP company_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_owner ADD user_id INT DEFAULT NULL, ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE email_owner ADD CONSTRAINT FK_522DA7F3979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE email_owner ADD CONSTRAINT FK_522DA7F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_522DA7F3A76ED395 ON email_owner (user_id)');
        $this->addSql('CREATE INDEX IDX_522DA7F3979B1AD6 ON email_owner (company_id)');
    }
}
