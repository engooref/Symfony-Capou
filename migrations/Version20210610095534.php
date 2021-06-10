<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610095534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piquet ADD id_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE piquet ADD CONSTRAINT FK_E099FEDBFA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_E099FEDBFA7089AB ON piquet (id_groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piquet DROP FOREIGN KEY FK_E099FEDBFA7089AB');
        $this->addSql('DROP INDEX IDX_E099FEDBFA7089AB ON piquet');
        $this->addSql('ALTER TABLE piquet DROP id_groupe_id');
    }
}
