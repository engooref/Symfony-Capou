<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610095216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupe_piquet');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_piquet (groupe_id INT NOT NULL, piquet_id INT NOT NULL, INDEX IDX_8BA702E17A45358C (groupe_id), INDEX IDX_8BA702E1E471F8D2 (piquet_id), PRIMARY KEY(groupe_id, piquet_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE groupe_piquet ADD CONSTRAINT FK_8BA702E17A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_piquet ADD CONSTRAINT FK_8BA702E1E471F8D2 FOREIGN KEY (piquet_id) REFERENCES piquet (id) ON DELETE CASCADE');
    }
}
