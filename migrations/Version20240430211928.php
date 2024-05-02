<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430211928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forecast ADD COLUMN latitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE forecast ADD COLUMN longitude DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__forecast AS SELECT id, forecast_days, forecast_time_hourly, forecast_temperature_hourly, date_added, date_created FROM forecast');
        $this->addSql('DROP TABLE forecast');
        $this->addSql('CREATE TABLE forecast (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, forecast_days INTEGER NOT NULL, forecast_time_hourly CLOB NOT NULL --(DC2Type:array)
        , forecast_temperature_hourly CLOB NOT NULL --(DC2Type:array)
        , date_added DATETIME NOT NULL, date_created DATETIME NOT NULL)');
        $this->addSql('INSERT INTO forecast (id, forecast_days, forecast_time_hourly, forecast_temperature_hourly, date_added, date_created) SELECT id, forecast_days, forecast_time_hourly, forecast_temperature_hourly, date_added, date_created FROM __temp__forecast');
        $this->addSql('DROP TABLE __temp__forecast');
    }
}
