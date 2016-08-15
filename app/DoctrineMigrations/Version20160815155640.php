<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160815155640 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE static_content DROP FOREIGN KEY FK_6747643B211923AF');
        $this->addSql('ALTER TABLE static_content ADD CONSTRAINT FK_6747643B211923AF FOREIGN KEY (last_edited_by_user_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE static_content DROP FOREIGN KEY FK_6747643B211923AF');
        $this->addSql('ALTER TABLE static_content ADD CONSTRAINT FK_6747643B211923AF FOREIGN KEY (last_edited_by_user_id) REFERENCES user (id)');
    }
}
