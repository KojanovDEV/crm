<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319072721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, email_id INT DEFAULT NULL, phone_number_id INT DEFAULT NULL, company_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4FBF094FA832C1C9 (email_id), UNIQUE INDEX UNIQ_4FBF094F39DFD528 (phone_number_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_owner (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, owner_type VARCHAR(50) NOT NULL, owner_id INT NOT NULL, UNIQUE INDEX unique_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_owner (id INT AUTO_INCREMENT NOT NULL, phone_number VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_3C3A93666B01BC5B (phone_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email_id INT DEFAULT NULL, phone_number_id INT DEFAULT NULL, workplace_id INT DEFAULT NULL, profile_photo_id INT DEFAULT NULL, fullname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_active_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649A832C1C9 (email_id), UNIQUE INDEX UNIQ_8D93D64939DFD528 (phone_number_id), INDEX IDX_8D93D649AC25FB46 (workplace_id), UNIQUE INDEX UNIQ_8D93D64987F42D3D (profile_photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA832C1C9 FOREIGN KEY (email_id) REFERENCES email_owner (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F39DFD528 FOREIGN KEY (phone_number_id) REFERENCES phone_owner (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A832C1C9 FOREIGN KEY (email_id) REFERENCES email_owner (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64939DFD528 FOREIGN KEY (phone_number_id) REFERENCES phone_owner (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AC25FB46 FOREIGN KEY (workplace_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64987F42D3D FOREIGN KEY (profile_photo_id) REFERENCES media_object (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA832C1C9');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F39DFD528');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A832C1C9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64939DFD528');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AC25FB46');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64987F42D3D');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE email_owner');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE phone_owner');
        $this->addSql('DROP TABLE user');
    }
}
