<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422091729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room_rating (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, room_id INT NOT NULL, comment LONGTEXT NOT NULL, rating INT NOT NULL, INDEX IDX_99C7E0E319EB6921 (client_id), INDEX IDX_99C7E0E354177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE room_rating ADD CONSTRAINT FK_99C7E0E319EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE room_rating ADD CONSTRAINT FK_99C7E0E354177093 FOREIGN KEY (room_id) REFERENCES salle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_rating DROP FOREIGN KEY FK_99C7E0E319EB6921');
        $this->addSql('ALTER TABLE room_rating DROP FOREIGN KEY FK_99C7E0E354177093');
        $this->addSql('DROP TABLE room_rating');
    }
}
