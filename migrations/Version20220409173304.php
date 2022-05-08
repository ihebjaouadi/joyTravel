<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409173304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chambre CHANGE type type VARCHAR(255) NOT NULL, CHANGE disponibilite disponibilite INT NOT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA76110FBA FOREIGN KEY (id_sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAD5412041 FOREIGN KEY (id_receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE equipement CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hotel ADD description LONGTEXT DEFAULT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL, CHANGE code_postal code_postal VARCHAR(255) NOT NULL, CHANGE complement_adresse complement_adresse VARCHAR(255) NOT NULL, CHANGE pays pays VARCHAR(255) NOT NULL, CHANGE nb_etoile nb_etoile VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chambre CHANGE type type VARCHAR(50) NOT NULL, CHANGE disponibilite disponibilite SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA76110FBA');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAD5412041');
        $this->addSql('ALTER TABLE equipement CHANGE nom nom VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE hotel DROP description, CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE adresse adresse VARCHAR(50) DEFAULT NULL, CHANGE ville ville VARCHAR(50) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE complement_adresse complement_adresse VARCHAR(50) DEFAULT NULL, CHANGE pays pays VARCHAR(50) DEFAULT NULL, CHANGE nb_etoile nb_etoile INT DEFAULT NULL');
    }
}
