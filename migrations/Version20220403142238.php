<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403142238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, id_hotel_id INT NOT NULL, type VARCHAR(50) NOT NULL, disponibilite SMALLINT NOT NULL, INDEX IDX_C509E4FF6298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_hotel_id INT NOT NULL, body VARCHAR(2000) NOT NULL, statue SMALLINT NOT NULL, INDEX IDX_4C62E63879F37AE5 (id_user_id), UNIQUE INDEX UNIQ_4C62E6386298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, id_chambre_id INT NOT NULL, reservee_du DATE NOT NULL, reservee_au DATE NOT NULL, INDEX IDX_2CBACE2F3E9DFF83 (id_chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_chambre_id INT NOT NULL, id_hotel_id INT NOT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_B8B4C6F33E9DFF83 (id_chambre_id), INDEX IDX_B8B4C6F36298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formule (id INT AUTO_INCREMENT NOT NULL, type_chambre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_chambre_id INT NOT NULL, id_formule_id INT NOT NULL, date_reservation DATE NOT NULL, date_arrivee DATE NOT NULL, date_depart DATE NOT NULL, nbr_personnes INT NOT NULL, prix_total DOUBLE PRECISION NOT NULL, INDEX IDX_42C8495579F37AE5 (id_user_id), INDEX IDX_42C849553E9DFF83 (id_chambre_id), UNIQUE INDEX UNIQ_42C849558FE27406 (id_formule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_hotel_id INT NOT NULL, vote INT NOT NULL, UNIQUE INDEX UNIQ_5A10856479F37AE5 (id_user_id), UNIQUE INDEX UNIQ_5A1085646298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF6298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6386298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F3E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F33E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F36298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558FE27406 FOREIGN KEY (id_formule_id) REFERENCES formule (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085646298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F3E9DFF83');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F33E9DFF83');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553E9DFF83');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558FE27406');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE formule');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE vote');
    }
}
