<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240204161040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
            CREATE TABLE service (
                id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
                name TINYTEXT NOT NULL,
                duration INT NOT NULL,
                capacity INT NOT NULL,
                description LONGTEXT NOT NULL,
                cancellation_limit INT NOT NULL,
                staff_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL,
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE service');
    }
}
