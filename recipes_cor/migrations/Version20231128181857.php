<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128181857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipes_ingredients (recipe_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(recipe_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_761206B059D8A214 ON recipes_ingredients (recipe_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_761206B0933FE08C ON recipes_ingredients (ingredient_id)');
        $this->addSql('ALTER TABLE recipes_ingredients ADD CONSTRAINT FK_761206B059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipes_ingredients ADD CONSTRAINT FK_761206B0933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recipes_ingredients DROP CONSTRAINT FK_761206B059D8A214');
        $this->addSql('ALTER TABLE recipes_ingredients DROP CONSTRAINT FK_761206B0933FE08C');
        $this->addSql('DROP TABLE recipes_ingredients');
    }
}
