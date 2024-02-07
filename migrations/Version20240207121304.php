<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207121304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
            CREATE TABLE reservation (
                user_name VARCHAR(128) NOT NULL,
                user_email VARCHAR(255) NOT NULL,
                user_id BINARY(16) DEFAULT NULL,
                event_id BINARY(16) DEFAULT NULL,
                description LONGTEXT NOT NULL,
                status text NOT NULL,
                id BINARY(16) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4
            SQL,
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation');
    }
}
