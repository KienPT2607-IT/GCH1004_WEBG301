<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220817031155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE record (id INT AUTO_INCREMENT NOT NULL, usename_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, date DATE NOT NULL, INDEX IDX_9B349F914EB434CD (usename_id), INDEX IDX_9B349F914584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F914EB434CD FOREIGN KEY (usename_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F914584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F914EB434CD');
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F914584665A');
        $this->addSql('DROP TABLE record');
    }
}
