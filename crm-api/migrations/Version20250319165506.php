<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319165506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A832C1C9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64939DFD528');
        $this->addSql('DROP INDEX UNIQ_8D93D649A832C1C9 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64939DFD528 ON user');
        $this->addSql('ALTER TABLE user ADD email_owner_id INT DEFAULT NULL, ADD phone_owner_id INT DEFAULT NULL, DROP email_id, DROP phone_number_id');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649755A7EB0 FOREIGN KEY (email_owner_id) REFERENCES email_owner (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B67B72F5 FOREIGN KEY (phone_owner_id) REFERENCES phone_owner (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649755A7EB0 ON user (email_owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B67B72F5 ON user (phone_owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649755A7EB0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B67B72F5');
        $this->addSql('DROP INDEX UNIQ_8D93D649755A7EB0 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649B67B72F5 ON user');
        $this->addSql('ALTER TABLE user ADD email_id INT DEFAULT NULL, ADD phone_number_id INT DEFAULT NULL, DROP email_owner_id, DROP phone_owner_id');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A832C1C9 FOREIGN KEY (email_id) REFERENCES email_owner (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64939DFD528 FOREIGN KEY (phone_number_id) REFERENCES phone_owner (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A832C1C9 ON user (email_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64939DFD528 ON user (phone_number_id)');
    }
}
