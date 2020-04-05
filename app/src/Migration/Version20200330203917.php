<?php
declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200330203917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создаем таблицу для записи новостей';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE latest_news
            (id mediumint NOT NULL AUTO_INCREMENT,
                  title varchar(1000),
                  news text,
                  img varchar(200),
                  add_time timestamp DEFAULT NOW(),
                  CONSTRAINT id PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
        ');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
