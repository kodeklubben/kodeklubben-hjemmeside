<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160815013413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutor DROP FOREIGN KEY FK_99074648A76ED395');
        $this->addSql('ALTER TABLE tutor DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE tutor ADD id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, ADD is_substitute TINYINT(1) DEFAULT NULL, CHANGE course_id course_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tutor ADD CONSTRAINT FK_99074648A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutor MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE tutor DROP FOREIGN KEY FK_99074648A76ED395');
        $this->addSql('ALTER TABLE tutor DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE tutor DROP id, DROP is_substitute, CHANGE user_id user_id INT NOT NULL, CHANGE course_id course_id INT NOT NULL');
        $this->addSql('ALTER TABLE tutor ADD CONSTRAINT FK_99074648A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tutor ADD PRIMARY KEY (course_id, user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec(
            'UPDATE tutor SET is_substitute = FALSE WHERE is_substitute IS NULL;'
        );
    }
}
