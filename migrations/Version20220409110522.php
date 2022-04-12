<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409110522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(30) NOT NULL, description VARCHAR(30) NOT NULL, body VARCHAR(1000) NOT NULL, date_creation DATE NOT NULL, INDEX IDX_BA5AE01DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_chambre (reservation_id INT NOT NULL, chambre_id INT NOT NULL, INDEX IDX_A29C5F7AB83297E7 (reservation_id), INDEX IDX_A29C5F7A9B177F54 (chambre_id), PRIMARY KEY(reservation_id, chambre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_chambre ADD CONSTRAINT FK_A29C5F7AB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_chambre ADD CONSTRAINT FK_A29C5F7A9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553E9DFF83');
        $this->addSql('DROP INDEX IDX_42C849553E9DFF83 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP id_chambre_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE reservation_chambre');
        $this->addSql('ALTER TABLE reservation ADD id_chambre_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
        $this->addSql('CREATE INDEX IDX_42C849553E9DFF83 ON reservation (id_chambre_id)');
    }
}
