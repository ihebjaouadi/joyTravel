<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414234729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact CHANGE body body LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6386298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('DROP INDEX fk_4c62e6386298578d ON contact');
        $this->addSql('CREATE INDEX IDX_4C62E6386298578D ON contact (id_hotel_id)');
        $this->addSql('ALTER TABLE hotel CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE adresse adresse VARCHAR(50) NOT NULL, CHANGE ville ville VARCHAR(50) NOT NULL, CHANGE code_postal code_postal INT NOT NULL, CHANGE complement_adresse complement_adresse VARCHAR(50) NOT NULL, CHANGE pays pays VARCHAR(50) NOT NULL, CHANGE nb_etoile nb_etoile INT NOT NULL');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY user_fk');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY ID_hotel_fk');
        $this->addSql('DROP INDEX user_fk ON vote');
        $this->addSql('CREATE INDEX IDX_5A10856479F37AE5 ON vote (id_user_id)');
        $this->addSql('DROP INDEX id_hotel_fk ON vote');
        $this->addSql('CREATE INDEX IDX_5A1085646298578D ON vote (id_hotel_id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT user_fk FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT ID_hotel_fk FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6386298578D');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6386298578D');
        $this->addSql('ALTER TABLE contact CHANGE body body VARCHAR(2000) NOT NULL');
        $this->addSql('DROP INDEX idx_4c62e6386298578d ON contact');
        $this->addSql('CREATE INDEX FK_4C62E6386298578D ON contact (id_hotel_id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6386298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE hotel CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE adresse adresse VARCHAR(50) DEFAULT NULL, CHANGE ville ville VARCHAR(50) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE complement_adresse complement_adresse VARCHAR(50) DEFAULT NULL, CHANGE pays pays VARCHAR(50) DEFAULT NULL, CHANGE nb_etoile nb_etoile INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856479F37AE5');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085646298578D');
        $this->addSql('DROP INDEX idx_5a10856479f37ae5 ON vote');
        $this->addSql('CREATE INDEX user_fk ON vote (id_user_id)');
        $this->addSql('DROP INDEX idx_5a1085646298578d ON vote');
        $this->addSql('CREATE INDEX ID_hotel_fk ON vote (id_hotel_id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085646298578D FOREIGN KEY (id_hotel_id) REFERENCES hotel (id)');
    }
}
