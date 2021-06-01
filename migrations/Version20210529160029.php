<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210529160029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnees_station DROP FOREIGN KEY FK_A19EBDBB843732E2');
        $this->addSql('ALTER TABLE groupe_station DROP FOREIGN KEY FK_595E1FFF21BDB235');
        $this->addSql('CREATE TABLE armoire (id INT NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_armoire (id INT AUTO_INCREMENT NOT NULL, id_armoire_id INT NOT NULL, pression DOUBLE PRECISION NOT NULL, horodatage DATETIME NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_ADD05B4AA97113F4 (id_armoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_armoire (groupe_id INT NOT NULL, armoire_id INT NOT NULL, INDEX IDX_5510F90E7A45358C (groupe_id), INDEX IDX_5510F90ECFB9323 (armoire_id), PRIMARY KEY(groupe_id, armoire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donnees_armoire ADD CONSTRAINT FK_ADD05B4AA97113F4 FOREIGN KEY (id_armoire_id) REFERENCES armoire (id)');
        $this->addSql('ALTER TABLE groupe_armoire ADD CONSTRAINT FK_5510F90E7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_armoire ADD CONSTRAINT FK_5510F90ECFB9323 FOREIGN KEY (armoire_id) REFERENCES armoire (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE donnees_station');
        $this->addSql('DROP TABLE groupe_station');
        $this->addSql('DROP TABLE station');
        $this->addSql('ALTER TABLE donnees_piquet ADD latitude DOUBLE PRECISION NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, DROP gps');
        $this->addSql('ALTER TABLE donnees_vanne ADD latitude DOUBLE PRECISION NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, DROP gps');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnees_armoire DROP FOREIGN KEY FK_ADD05B4AA97113F4');
        $this->addSql('ALTER TABLE groupe_armoire DROP FOREIGN KEY FK_5510F90ECFB9323');
        $this->addSql('CREATE TABLE donnees_station (id INT AUTO_INCREMENT NOT NULL, id_station_id INT NOT NULL, pression DOUBLE PRECISION NOT NULL, gps VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, horodatage DATETIME NOT NULL, INDEX IDX_A19EBDBB843732E2 (id_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe_station (groupe_id INT NOT NULL, station_id INT NOT NULL, INDEX IDX_595E1FFF7A45358C (groupe_id), INDEX IDX_595E1FFF21BDB235 (station_id), PRIMARY KEY(groupe_id, station_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE station (id INT NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE donnees_station ADD CONSTRAINT FK_A19EBDBB843732E2 FOREIGN KEY (id_station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE groupe_station ADD CONSTRAINT FK_595E1FFF21BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_station ADD CONSTRAINT FK_595E1FFF7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE armoire');
        $this->addSql('DROP TABLE donnees_armoire');
        $this->addSql('DROP TABLE groupe_armoire');
        $this->addSql('ALTER TABLE donnees_piquet ADD gps VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP latitude, DROP longitude');
        $this->addSql('ALTER TABLE donnees_vanne ADD gps VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP latitude, DROP longitude');
    }
}
