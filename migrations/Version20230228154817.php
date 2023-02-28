<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228154817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE licencie_equipe (licencie_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_E1F42B84B56DCD74 (licencie_id), INDEX IDX_E1F42B846D861B89 (equipe_id), PRIMARY KEY(licencie_id, equipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE licencie_equipe ADD CONSTRAINT FK_E1F42B84B56DCD74 FOREIGN KEY (licencie_id) REFERENCES licencie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE licencie_equipe ADD CONSTRAINT FK_E1F42B846D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licencie_equipe DROP FOREIGN KEY FK_E1F42B84B56DCD74');
        $this->addSql('ALTER TABLE licencie_equipe DROP FOREIGN KEY FK_E1F42B846D861B89');
        $this->addSql('DROP TABLE licencie_equipe');
    }
}
