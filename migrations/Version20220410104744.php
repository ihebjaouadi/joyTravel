<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410104744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558FE27406');
        $this->addSql('DROP INDEX UNIQ_42C849558FE27406 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD id_formule VARCHAR(50) NOT NULL, DROP id_formule_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD id_formule_id INT NOT NULL, DROP id_formule');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558FE27406 FOREIGN KEY (id_formule_id) REFERENCES formule (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C849558FE27406 ON reservation (id_formule_id)');
    }
}
