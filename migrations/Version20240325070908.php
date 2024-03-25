<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240325070908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration creates Products table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE products (
          id INT NOT NULL,
          name VARCHAR(255) NOT NULL,
          brand VARCHAR(255) DEFAULT NULL,
          description VARCHAR(10) NOT NULL,
          protein NUMERIC(3, 1) NOT NULL,
          carbs NUMERIC(3, 1) NOT NULL,
          fat NUMERIC(3, 1) NOT NULL,
          sugar NUMERIC(3, 1) NOT NULL,
          amount NUMERIC(5, 0) NOT NULL,
          is_liquid BOOLEAN DEFAULT NULL,
          PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('DROP TABLE products');
    }
}
