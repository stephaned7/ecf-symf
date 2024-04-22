<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422152103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_rating ADD room_id INT NOT NULL');
        $this->addSql('ALTER TABLE room_rating ADD CONSTRAINT FK_99C7E0E354177093 FOREIGN KEY (room_id) REFERENCES salle (id)');
        $this->addSql('CREATE INDEX IDX_99C7E0E354177093 ON room_rating (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_rating DROP FOREIGN KEY FK_99C7E0E354177093');
        $this->addSql('DROP INDEX IDX_99C7E0E354177093 ON room_rating');
        $this->addSql('ALTER TABLE room_rating DROP room_id');
    }
}
