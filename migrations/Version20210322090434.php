<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322090434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE centrale (id INT NOT NULL, ip VARCHAR(255) NOT NULL, connected TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_piquet (id INT AUTO_INCREMENT NOT NULL, id_piquet_id INT NOT NULL, horodatage DATETIME NOT NULL, humidite LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', temperature DOUBLE PRECISION NOT NULL, gps VARCHAR(255) NOT NULL, INDEX IDX_29AB2F3C644444F5 (id_piquet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_station (id INT AUTO_INCREMENT NOT NULL, id_station_id INT NOT NULL, pression DOUBLE PRECISION NOT NULL, gps VARCHAR(255) NOT NULL, horodatage DATETIME NOT NULL, INDEX IDX_A19EBDBB843732E2 (id_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donnees_vanne (id INT AUTO_INCREMENT NOT NULL, id_vanne_id INT NOT NULL, debit DOUBLE PRECISION NOT NULL, gps VARCHAR(255) NOT NULL, horodatage DATETIME NOT NULL, INDEX IDX_893426B3F30350AF (id_vanne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE electro_vanne (id INT NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_piquet (groupe_id INT NOT NULL, piquet_id INT NOT NULL, INDEX IDX_8BA702E17A45358C (groupe_id), INDEX IDX_8BA702E1E471F8D2 (piquet_id), PRIMARY KEY(groupe_id, piquet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_electro_vanne (groupe_id INT NOT NULL, electro_vanne_id INT NOT NULL, INDEX IDX_42DEC137A45358C (groupe_id), INDEX IDX_42DEC13D9D043A7 (electro_vanne_id), PRIMARY KEY(groupe_id, electro_vanne_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_station (groupe_id INT NOT NULL, station_id INT NOT NULL, INDEX IDX_595E1FFF7A45358C (groupe_id), INDEX IDX_595E1FFF21BDB235 (station_id), PRIMARY KEY(groupe_id, station_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operateur (id INT AUTO_INCREMENT NOT NULL, id_groupe_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B4B7F99DE7927C74 (email), INDEX IDX_B4B7F99DFA7089AB (id_groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piquet (id INT NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id INT NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donnees_piquet ADD CONSTRAINT FK_29AB2F3C644444F5 FOREIGN KEY (id_piquet_id) REFERENCES piquet (id)');
        $this->addSql('ALTER TABLE donnees_station ADD CONSTRAINT FK_A19EBDBB843732E2 FOREIGN KEY (id_station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE donnees_vanne ADD CONSTRAINT FK_893426B3F30350AF FOREIGN KEY (id_vanne_id) REFERENCES electro_vanne (id)');
        $this->addSql('ALTER TABLE groupe_piquet ADD CONSTRAINT FK_8BA702E17A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_piquet ADD CONSTRAINT FK_8BA702E1E471F8D2 FOREIGN KEY (piquet_id) REFERENCES piquet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_electro_vanne ADD CONSTRAINT FK_42DEC137A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_electro_vanne ADD CONSTRAINT FK_42DEC13D9D043A7 FOREIGN KEY (electro_vanne_id) REFERENCES electro_vanne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_station ADD CONSTRAINT FK_595E1FFF7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_station ADD CONSTRAINT FK_595E1FFF21BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE operateur ADD CONSTRAINT FK_B4B7F99DFA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnees_vanne DROP FOREIGN KEY FK_893426B3F30350AF');
        $this->addSql('ALTER TABLE groupe_electro_vanne DROP FOREIGN KEY FK_42DEC13D9D043A7');
        $this->addSql('ALTER TABLE donnees_piquet DROP FOREIGN KEY FK_29AB2F3C644444F5');
        $this->addSql('ALTER TABLE groupe_piquet DROP FOREIGN KEY FK_8BA702E1E471F8D2');
        $this->addSql('ALTER TABLE donnees_station DROP FOREIGN KEY FK_A19EBDBB843732E2');
        $this->addSql('ALTER TABLE groupe_station DROP FOREIGN KEY FK_595E1FFF21BDB235');
        $this->addSql('DROP TABLE centrale');
        $this->addSql('DROP TABLE donnees_piquet');
        $this->addSql('DROP TABLE donnees_station');
        $this->addSql('DROP TABLE donnees_vanne');
        $this->addSql('DROP TABLE electro_vanne');
        $this->addSql('DROP TABLE groupe_piquet');
        $this->addSql('DROP TABLE groupe_electro_vanne');
        $this->addSql('DROP TABLE groupe_station');
        $this->addSql('DROP TABLE operateur');
        $this->addSql('DROP TABLE piquet');
        $this->addSql('DROP TABLE station');
    }
}
