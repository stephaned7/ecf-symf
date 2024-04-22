<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422144006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_salle DROP FOREIGN KEY FK_395FFF22A32EFC6');
        $this->addSql('ALTER TABLE avis_salle DROP FOREIGN KEY FK_395FFF22DC304035');
        $this->addSql('DROP TABLE avis_salle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis_salle (id INT AUTO_INCREMENT NOT NULL, salle_id INT NOT NULL, rating_id INT NOT NULL, UNIQUE INDEX UNIQ_395FFF22A32EFC6 (rating_id), INDEX IDX_395FFF22DC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE avis_salle ADD CONSTRAINT FK_395FFF22A32EFC6 FOREIGN KEY (rating_id) REFERENCES room_rating (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE avis_salle ADD CONSTRAINT FK_395FFF22DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
