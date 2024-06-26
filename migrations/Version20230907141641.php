<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907141641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images ADD recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A59D8A214 ON images (recipe_id)');
        $this->addSql('ALTER TABLE recipe DROP image_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A59D8A214');
        $this->addSql('DROP INDEX IDX_E01FBE6A59D8A214 ON images');
        $this->addSql('ALTER TABLE images DROP recipe_id');
    }
}
