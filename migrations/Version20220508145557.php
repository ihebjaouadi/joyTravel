<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220508145557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_event (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postlike (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, user_id INT DEFAULT NULL, value INT NOT NULL, INDEX IDX_B84FD43A4B89032C (post_id), INDEX IDX_B84FD43AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_contact_id INT NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC7422BA59D (id_contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postlike ADD CONSTRAINT FK_B84FD43A4B89032C FOREIGN KEY (post_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE postlike ADD CONSTRAINT FK_B84FD43AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7422BA59D FOREIGN KEY (id_contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chambre CHANGE type type VARCHAR(255) NOT NULL, CHANGE disponibilite disponibilite INT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE message message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE contact DROP INDEX UNIQ_4C62E6386298578D, ADD INDEX IDX_4C62E6386298578D (id_hotel_id)');
        $this->addSql('ALTER TABLE contact CHANGE body body LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipement CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD category_id INT DEFAULT NULL, ADD description LONGTEXT NOT NULL, ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E12469DE2 FOREIGN KEY (category_id) REFERENCES category_event (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B26681E12469DE2 ON evenement (category_id)');
        $this->addSql('ALTER TABLE hotel ADD description LONGTEXT DEFAULT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL, CHANGE code_postal code_postal VARCHAR(255) NOT NULL, CHANGE complement_adresse complement_adresse VARCHAR(255) NOT NULL, CHANGE pays pays VARCHAR(255) NOT NULL, CHANGE nb_etoile nb_etoile VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_116109812C115A61');
        $this->addSql('ALTER TABLE reservation_evenement CHANGE id_evenement_id id_evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_116109812C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote DROP INDEX UNIQ_5A10856479F37AE5, ADD INDEX IDX_5A10856479F37AE5 (id_user_id)');
        $this->addSql('ALTER TABLE vote DROP INDEX UNIQ_5A1085646298578D, ADD INDEX IDX_5A1085646298578D (id_hotel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E12469DE2');
        $this->addSql('DROP TABLE category_event');
        $this->addSql('DROP TABLE postlike');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE chambre CHANGE type type VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE disponibilite disponibilite SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE message message VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaire CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE contact DROP INDEX IDX_4C62E6386298578D, ADD UNIQUE INDEX UNIQ_4C62E6386298578D (id_hotel_id)');
        $this->addSql('ALTER TABLE contact CHANGE body body VARCHAR(2000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE equipement CHANGE nom nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_B26681E12469DE2 ON evenement');
        $this->addSql('ALTER TABLE evenement DROP category_id, DROP description, DROP img');
        $this->addSql('ALTER TABLE hotel DROP description, CHANGE nom nom VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse adresse VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE ville ville VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE complement_adresse complement_adresse VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pays pays VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nb_etoile nb_etoile INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_116109812C115A61');
        $this->addSql('ALTER TABLE reservation_evenement CHANGE id_evenement_id id_evenement_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_116109812C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE vote DROP INDEX IDX_5A10856479F37AE5, ADD UNIQUE INDEX UNIQ_5A10856479F37AE5 (id_user_id)');
        $this->addSql('ALTER TABLE vote DROP INDEX IDX_5A1085646298578D, ADD UNIQUE INDEX UNIQ_5A1085646298578D (id_hotel_id)');
    }
}
