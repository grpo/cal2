<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240325164526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE recipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipe_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE recipe (
          id INT NOT NULL,
          recipe_name VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          preparation_instructions VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE recipe_products (
          id INT NOT NULL,
          recipe_id INT NOT NULL,
          product_id INT NOT NULL,
          quantity INT NOT NULL,
          unit_of_measurement INT NOT NULL,
          PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE recipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipe_products_id_seq CASCADE');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_products');
    }
}
