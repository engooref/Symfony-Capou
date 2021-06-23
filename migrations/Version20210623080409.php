<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623080409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE armoire (id INT NOT NULL, id_parcelle_id INT DEFAULT NULL, etat INT NOT NULL, ip VARCHAR(255) NOT NULL, INDEX IDX_93771E405C212091 (id_parcelle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_armoire (id INT AUTO_INCREMENT NOT NULL, id_armoire_id INT NOT NULL, batterie SMALLINT NOT NULL, INDEX IDX_ADD05B4AA97113F4 (id_armoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_piquet (id INT AUTO_INCREMENT NOT NULL, id_piquet_id INT NOT NULL, temperature DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, horodatage DATETIME NOT NULL, humidite LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', batterie SMALLINT NOT NULL, INDEX IDX_29AB2F3C644444F5 (id_piquet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_vanne (id INT AUTO_INCREMENT NOT NULL, id_vanne_id INT NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, horodatage DATETIME NOT NULL, batterie SMALLINT NOT NULL, INDEX IDX_893426B3F30350AF (id_vanne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE electro_vanne (id INT NOT NULL, id_parcelle_id INT DEFAULT NULL, etat TINYINT(1) NOT NULL, ip VARCHAR(255) NOT NULL, INDEX IDX_9858B9885C212091 (id_parcelle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operateur (id INT AUTO_INCREMENT NOT NULL, id_parcelle_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, reset_token VARCHAR(50) DEFAULT NULL, verified_by_admin TINYINT(1) NOT NULL, is_first_connexion TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B4B7F99DE7927C74 (email), INDEX IDX_B4B7F99D5C212091 (id_parcelle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcelle (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piquet (id INT NOT NULL, id_parcelle_id INT DEFAULT NULL, id_maitre_radio_id INT DEFAULT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_E099FEDB5C212091 (id_parcelle_id), INDEX IDX_E099FEDB3D982BF8 (id_maitre_radio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE armoire ADD CONSTRAINT FK_93771E405C212091 FOREIGN KEY (id_parcelle_id) REFERENCES parcelle (id)');
        $this->addSql('ALTER TABLE donnees_armoire ADD CONSTRAINT FK_ADD05B4AA97113F4 FOREIGN KEY (id_armoire_id) REFERENCES armoire (id)');
        $this->addSql('ALTER TABLE donnees_piquet ADD CONSTRAINT FK_29AB2F3C644444F5 FOREIGN KEY (id_piquet_id) REFERENCES piquet (id)');
        $this->addSql('ALTER TABLE donnees_vanne ADD CONSTRAINT FK_893426B3F30350AF FOREIGN KEY (id_vanne_id) REFERENCES electro_vanne (id)');
        $this->addSql('ALTER TABLE electro_vanne ADD CONSTRAINT FK_9858B9885C212091 FOREIGN KEY (id_parcelle_id) REFERENCES parcelle (id)');
        $this->addSql('ALTER TABLE operateur ADD CONSTRAINT FK_B4B7F99D5C212091 FOREIGN KEY (id_parcelle_id) REFERENCES parcelle (id)');
        $this->addSql('ALTER TABLE piquet ADD CONSTRAINT FK_E099FEDB5C212091 FOREIGN KEY (id_parcelle_id) REFERENCES parcelle (id)');
        $this->addSql('ALTER TABLE piquet ADD CONSTRAINT FK_E099FEDB3D982BF8 FOREIGN KEY (id_maitre_radio_id) REFERENCES electro_vanne (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnees_armoire DROP FOREIGN KEY FK_ADD05B4AA97113F4');
        $this->addSql('ALTER TABLE donnees_vanne DROP FOREIGN KEY FK_893426B3F30350AF');
        $this->addSql('ALTER TABLE piquet DROP FOREIGN KEY FK_E099FEDB3D982BF8');
        $this->addSql('ALTER TABLE armoire DROP FOREIGN KEY FK_93771E405C212091');
        $this->addSql('ALTER TABLE electro_vanne DROP FOREIGN KEY FK_9858B9885C212091');
        $this->addSql('ALTER TABLE operateur DROP FOREIGN KEY FK_B4B7F99D5C212091');
        $this->addSql('ALTER TABLE piquet DROP FOREIGN KEY FK_E099FEDB5C212091');
        $this->addSql('ALTER TABLE donnees_piquet DROP FOREIGN KEY FK_29AB2F3C644444F5');
        $this->addSql('DROP TABLE armoire');
        $this->addSql('DROP TABLE donnees_armoire');
        $this->addSql('DROP TABLE donnees_piquet');
        $this->addSql('DROP TABLE donnees_vanne');
        $this->addSql('DROP TABLE electro_vanne');
        $this->addSql('DROP TABLE operateur');
        $this->addSql('DROP TABLE parcelle');
        $this->addSql('DROP TABLE piquet');
    }
}
