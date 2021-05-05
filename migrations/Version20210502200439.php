<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502200439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centrale DROP connected');
        $this->addSql('ALTER TABLE piquet ADD CONSTRAINT FK_E099FEDB40B31AE8 FOREIGN KEY (id_centrale_id) REFERENCES centrale (id)');
        $this->addSql('CREATE INDEX IDX_E099FEDB40B31AE8 ON piquet (id_centrale_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centrale ADD connected TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE piquet DROP FOREIGN KEY FK_E099FEDB40B31AE8');
        $this->addSql('DROP INDEX IDX_E099FEDB40B31AE8 ON piquet');
    }
}
