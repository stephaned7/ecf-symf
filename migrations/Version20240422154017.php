<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422154017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_salle DROP FOREIGN KEY FK_2B87DD8B26ED0855');
        $this->addSql('ALTER TABLE note_salle DROP FOREIGN KEY FK_2B87DD8BDC304035');
        $this->addSql('ALTER TABLE note_room_rating DROP FOREIGN KEY FK_BF7E776626ED0855');
        $this->addSql('ALTER TABLE note_room_rating DROP FOREIGN KEY FK_BF7E7766969B0FAF');
        $this->addSql('DROP TABLE note_salle');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE note_room_rating');
        $this->addSql('ALTER TABLE room_rating ADD CONSTRAINT FK_99C7E0E354177093 FOREIGN KEY (room_id) REFERENCES salle (id)');
        $this->addSql('CREATE INDEX IDX_99C7E0E354177093 ON room_rating (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note_salle (note_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_2B87DD8B26ED0855 (note_id), INDEX IDX_2B87DD8BDC304035 (salle_id), PRIMARY KEY(note_id, salle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE note_room_rating (note_id INT NOT NULL, room_rating_id INT NOT NULL, INDEX IDX_BF7E776626ED0855 (note_id), INDEX IDX_BF7E7766969B0FAF (room_rating_id), PRIMARY KEY(note_id, room_rating_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE note_salle ADD CONSTRAINT FK_2B87DD8B26ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_salle ADD CONSTRAINT FK_2B87DD8BDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_room_rating ADD CONSTRAINT FK_BF7E776626ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_room_rating ADD CONSTRAINT FK_BF7E7766969B0FAF FOREIGN KEY (room_rating_id) REFERENCES room_rating (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_rating DROP FOREIGN KEY FK_99C7E0E354177093');
        $this->addSql('DROP INDEX IDX_99C7E0E354177093 ON room_rating');
    }
}
