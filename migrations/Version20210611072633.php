<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611072633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupe_electro_vanne');
        $this->addSql('ALTER TABLE electro_vanne ADD id_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE electro_vanne ADD CONSTRAINT FK_9858B988FA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_9858B988FA7089AB ON electro_vanne (id_groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_electro_vanne (groupe_id INT NOT NULL, electro_vanne_id INT NOT NULL, INDEX IDX_42DEC137A45358C (groupe_id), INDEX IDX_42DEC13D9D043A7 (electro_vanne_id), PRIMARY KEY(groupe_id, electro_vanne_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE groupe_electro_vanne ADD CONSTRAINT FK_42DEC137A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_electro_vanne ADD CONSTRAINT FK_42DEC13D9D043A7 FOREIGN KEY (electro_vanne_id) REFERENCES electro_vanne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE electro_vanne DROP FOREIGN KEY FK_9858B988FA7089AB');
        $this->addSql('DROP INDEX IDX_9858B988FA7089AB ON electro_vanne');
        $this->addSql('ALTER TABLE electro_vanne DROP id_groupe_id');
    }
}
