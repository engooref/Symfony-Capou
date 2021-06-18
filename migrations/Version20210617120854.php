<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617120854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupe_armoire');
        $this->addSql('ALTER TABLE armoire ADD id_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE armoire ADD CONSTRAINT FK_93771E40FA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_93771E40FA7089AB ON armoire (id_groupe_id)');
        $this->addSql('ALTER TABLE electro_vanne CHANGE id_centrale_id id_centrale_id INT NOT NULL');
        $this->addSql('ALTER TABLE piquet CHANGE id_centrale_id id_centrale_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_armoire (groupe_id INT NOT NULL, armoire_id INT NOT NULL, INDEX IDX_5510F90E7A45358C (groupe_id), INDEX IDX_5510F90ECFB9323 (armoire_id), PRIMARY KEY(groupe_id, armoire_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE groupe_armoire ADD CONSTRAINT FK_5510F90E7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_armoire ADD CONSTRAINT FK_5510F90ECFB9323 FOREIGN KEY (armoire_id) REFERENCES armoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE armoire DROP FOREIGN KEY FK_93771E40FA7089AB');
        $this->addSql('DROP INDEX IDX_93771E40FA7089AB ON armoire');
        $this->addSql('ALTER TABLE armoire DROP id_groupe_id');
        $this->addSql('ALTER TABLE electro_vanne CHANGE id_centrale_id id_centrale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE piquet CHANGE id_centrale_id id_centrale_id INT DEFAULT NULL');
    }
}
