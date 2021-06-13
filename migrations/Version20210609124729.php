<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210609124729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE electro_vanne ADD id_centrale_id INT NOT NULL');
        $this->addSql('ALTER TABLE electro_vanne ADD CONSTRAINT FK_9858B98840B31AE8 FOREIGN KEY (id_centrale_id) REFERENCES centrale (id)');
        $this->addSql('CREATE INDEX IDX_9858B98840B31AE8 ON electro_vanne (id_centrale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE electro_vanne DROP FOREIGN KEY FK_9858B98840B31AE8');
        $this->addSql('DROP INDEX IDX_9858B98840B31AE8 ON electro_vanne');
        $this->addSql('ALTER TABLE electro_vanne DROP id_centrale_id');
    }
}
