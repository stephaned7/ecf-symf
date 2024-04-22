<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422141601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_rating DROP FOREIGN KEY FK_99C7E0E3DC304035');
        $this->addSql('DROP INDEX IDX_99C7E0E3DC304035 ON room_rating');
        $this->addSql('ALTER TABLE room_rating DROP salle_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_rating ADD salle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room_rating ADD CONSTRAINT FK_99C7E0E3DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_99C7E0E3DC304035 ON room_rating (salle_id)');
    }
}
