<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319143928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_owner ADD user_id INT DEFAULT NULL, ADD company_id INT DEFAULT NULL, DROP owner_type, DROP owner_id');
        $this->addSql('ALTER TABLE email_owner ADD CONSTRAINT FK_522DA7F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE email_owner ADD CONSTRAINT FK_522DA7F3979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_522DA7F3A76ED395 ON email_owner (user_id)');
        $this->addSql('CREATE INDEX IDX_522DA7F3979B1AD6 ON email_owner (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_owner DROP FOREIGN KEY FK_522DA7F3A76ED395');
        $this->addSql('ALTER TABLE email_owner DROP FOREIGN KEY FK_522DA7F3979B1AD6');
        $this->addSql('DROP INDEX IDX_522DA7F3A76ED395 ON email_owner');
        $this->addSql('DROP INDEX IDX_522DA7F3979B1AD6 ON email_owner');
        $this->addSql('ALTER TABLE email_owner ADD owner_type VARCHAR(50) NOT NULL, ADD owner_id INT NOT NULL, DROP user_id, DROP company_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
    }
}
