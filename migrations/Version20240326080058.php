<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240326080058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipes_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE products (
          id INT NOT NULL,
          name VARCHAR(255) NOT NULL,
          brand VARCHAR(255) DEFAULT NULL,
          description VARCHAR(10) NOT NULL,
          protein NUMERIC(4, 1) NOT NULL,
          carbs NUMERIC(4, 1) NOT NULL,
          fat NUMERIC(4, 1) NOT NULL,
          sugar NUMERIC(4, 1) NOT NULL,
          amount NUMERIC(5, 0) NOT NULL,
          is_liquid BOOLEAN DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE recipes (
          id INT NOT NULL,
          recipe_name VARCHAR(255) NOT NULL,
          description VARCHAR(255) DEFAULT NULL,
          preparation_instructions VARCHAR(255) DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE recipes_products (
          id INT NOT NULL,
          recipe_id INT NOT NULL,
          product_id INT NOT NULL,
          quantity NUMERIC(6, 1) NOT NULL,
          unit_of_measurement VARCHAR(15) NOT NULL,
          PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipes_products_id_seq CASCADE');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE recipes');
        $this->addSql('DROP TABLE recipes_products');
    }
}
