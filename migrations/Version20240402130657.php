<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402130657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638A832C1C9');
        $this->addSql('DROP INDEX UNIQ_4C62E638A832C1C9 ON contact');
        $this->addSql('ALTER TABLE contact ADD name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, DROP email_id, CHANGE message message LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD email_id INT DEFAULT NULL, DROP name, DROP email, CHANGE message message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A832C1C9 FOREIGN KEY (email_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638A832C1C9 ON contact (email_id)');
    }
}
