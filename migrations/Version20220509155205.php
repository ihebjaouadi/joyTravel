<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509155205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, id_hotel_id INT NOT NULL, type VARCHAR(255) NOT NULL, disponibilite INT NOT NULL, INDEX IDX_C509E4FF6298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, id_sender_id INT NOT NULL, id_receiver_id INT NOT NULL, message VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_659DF2AA76110FBA (id_sender_id), INDEX IDX_659DF2AAD5412041 (id_receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_hotel_id INT NOT NULL, date DATETIME NOT NULL, content VARCHAR(500) NOT NULL, INDEX IDX_67F068BC79F37AE5 (id_user_id), INDEX IDX_67F068BC6298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_hotel_id INT NOT NULL, body LONGTEXT DEFAULT NULL, statue SMALLINT NOT NULL, INDEX IDX_4C62E63879F37AE5 (id_user_id), INDEX IDX_4C62E6386298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, id_chambre_id INT NOT NULL, reservee_du DATE NOT NULL, reservee_au DATE NOT NULL, INDEX IDX_2CBACE2F3E9DFF83 (id_chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_chambre_id INT NOT NULL, id_hotel_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_B8B4C6F33E9DFF83 (id_chambre_id), INDEX IDX_B8B4C6F36298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, id_hotel_id INT NOT NULL, nom VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, nombre_participants INT NOT NULL, INDEX IDX_B26681E6298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formule (id INT AUTO_INCREMENT NOT NULL, type_chambre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, complement_adresse VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, nb_etoile VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, id_hotel_id INT DEFAULT NULL, chemain VARCHAR(255) NOT NULL, INDEX IDX_C53D045F6298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PostLike (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, user_id INT DEFAULT NULL, value INT NOT NULL, INDEX IDX_B84FD43A4B89032C (post_id), INDEX IDX_B84FD43AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_contact_id INT NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC7422BA59D (id_contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_chambre_id INT NOT NULL, id_formule_id INT NOT NULL, date_reservation DATE NOT NULL, date_arrivee DATE NOT NULL, date_depart DATE NOT NULL, nbr_personnes INT NOT NULL, prix_total DOUBLE PRECISION NOT NULL, INDEX IDX_42C8495579F37AE5 (id_user_id), INDEX IDX_42C849553E9DFF83 (id_chambre_id), UNIQUE INDEX UNIQ_42C849558FE27406 (id_formule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_evenement (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_evenement_id INT NOT NULL, INDEX IDX_1161098179F37AE5 (id_user_id), INDEX IDX_116109812C115A61 (id_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_hotel_id INT NOT NULL, vote INT NOT NULL, INDEX IDX_5A10856479F37AE5 (id_user_id), INDEX IDX_5A1085646298578D (id_hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF6298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA76110FBA FOREIGN KEY (id_sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAD5412041 FOREIGN KEY (id_receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC6298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6386298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F3E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F33E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F36298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F6298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE PostLike ADD CONSTRAINT FK_B84FD43A4B89032C FOREIGN KEY (post_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE PostLike ADD CONSTRAINT FK_B84FD43AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7422BA59D FOREIGN KEY (id_contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558FE27406 FOREIGN KEY (id_formule_id) REFERENCES formule (id)');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_1161098179F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_116109812C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085646298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F3E9DFF83');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F33E9DFF83');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553E9DFF83');
        $this->addSql('ALTER TABLE PostLike DROP FOREIGN KEY FK_B84FD43A4B89032C');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7422BA59D');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_116109812C115A61');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558FE27406');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF6298578D');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC6298578D');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6386298578D');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F36298578D');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6298578D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F6298578D');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085646298578D');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA76110FBA');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAD5412041');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63879F37AE5');
        $this->addSql('ALTER TABLE PostLike DROP FOREIGN KEY FK_B84FD43AA76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_1161098179F37AE5');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856479F37AE5');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE formule');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE PostLike');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_evenement');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vote');
    }
}
