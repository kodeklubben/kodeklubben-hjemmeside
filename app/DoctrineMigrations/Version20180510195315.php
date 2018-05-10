<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180510195315 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE sponsor (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_818CC9D461190A32 ON sponsor (club_id)');
        $this->addSql('CREATE UNIQUE INDEX sponsor_club_name_idx ON sponsor (club_id, name)');
        $this->addSql('DROP INDEX IDX_B503165CDD62C21B');
        $this->addSql('DROP INDEX IDX_B503165CA76ED395');
        $this->addSql('DROP INDEX IDX_B503165C591CC992');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course_queue_entity AS SELECT id, course_id, user_id, child_id FROM course_queue_entity');
        $this->addSql('DROP TABLE course_queue_entity');
        $this->addSql('CREATE TABLE course_queue_entity (id INTEGER NOT NULL, course_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, child_id INTEGER DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_B503165C591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B503165CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B503165CDD62C21B FOREIGN KEY (child_id) REFERENCES child (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO course_queue_entity (id, course_id, user_id, child_id) SELECT id, course_id, user_id, child_id FROM __temp__course_queue_entity');
        $this->addSql('DROP TABLE __temp__course_queue_entity');
        $this->addSql('CREATE INDEX IDX_B503165CDD62C21B ON course_queue_entity (child_id)');
        $this->addSql('CREATE INDEX IDX_B503165CA76ED395 ON course_queue_entity (user_id)');
        $this->addSql('CREATE INDEX IDX_B503165C591CC992 ON course_queue_entity (course_id)');
        $this->addSql('DROP INDEX IDX_169E6FB9CD8F897F');
        $this->addSql('DROP INDEX IDX_169E6FB94A798B6F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course AS SELECT id, semester_id, course_type_id, name, description, participant_limit, deleted FROM course');
        $this->addSql('DROP TABLE course');
        $this->addSql('CREATE TABLE course (id INTEGER NOT NULL, semester_id INTEGER DEFAULT NULL, course_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, participant_limit INTEGER NOT NULL, deleted BOOLEAN DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_169E6FB94A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_169E6FB9CD8F897F FOREIGN KEY (course_type_id) REFERENCES course_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO course (id, semester_id, course_type_id, name, description, participant_limit, deleted) SELECT id, semester_id, course_type_id, name, description, participant_limit, deleted FROM __temp__course');
        $this->addSql('DROP TABLE __temp__course');
        $this->addSql('CREATE INDEX IDX_169E6FB9CD8F897F ON course (course_type_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB94A798B6F ON course (semester_id)');
        $this->addSql('DROP INDEX IDX_D79F6B11A76ED395');
        $this->addSql('DROP INDEX IDX_D79F6B11591CC992');
        $this->addSql('DROP INDEX IDX_D79F6B11DD62C21B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__participant AS SELECT id, child_id, course_id, user_id, created_at FROM participant');
        $this->addSql('DROP TABLE participant');
        $this->addSql('CREATE TABLE participant (id INTEGER NOT NULL, child_id INTEGER DEFAULT NULL, course_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_D79F6B11DD62C21B FOREIGN KEY (child_id) REFERENCES child (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D79F6B11591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D79F6B11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO participant (id, child_id, course_id, user_id, created_at) SELECT id, child_id, course_id, user_id, created_at FROM __temp__participant');
        $this->addSql('DROP TABLE __temp__participant');
        $this->addSql('CREATE INDEX IDX_D79F6B11A76ED395 ON participant (user_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11591CC992 ON participant (course_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11DD62C21B ON participant (child_id)');
        $this->addSql('DROP INDEX IDX_B6BD307F61190A32');
        $this->addSql('CREATE TEMPORARY TABLE __temp__message AS SELECT id, club_id, message, timestamp, expireDate FROM message');
        $this->addSql('DROP TABLE message');
        $this->addSql('CREATE TABLE message (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, message CLOB NOT NULL COLLATE BINARY, timestamp DATETIME NOT NULL, expireDate DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_B6BD307F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO message (id, club_id, message, timestamp, expireDate) SELECT id, club_id, message, timestamp, expireDate FROM __temp__message');
        $this->addSql('DROP TABLE __temp__message');
        $this->addSql('CREATE INDEX IDX_B6BD307F61190A32 ON message (club_id)');
        $this->addSql('DROP INDEX club_name_idx');
        $this->addSql('DROP INDEX IDX_C53D045F61190A32');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, club_id, file_name, file_path, name FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, file_name VARCHAR(255) NOT NULL COLLATE BINARY, file_path VARCHAR(255) NOT NULL COLLATE BINARY, name VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_C53D045F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image (id, club_id, file_name, file_path, name) SELECT id, club_id, file_name, file_path, name FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE UNIQUE INDEX club_name_idx ON image (club_id, name)');
        $this->addSql('CREATE INDEX IDX_C53D045F61190A32 ON image (club_id)');
        $this->addSql('DROP INDEX IDX_22B35429727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__child AS SELECT id, parent_id, firstName, lastName FROM child');
        $this->addSql('DROP TABLE child');
        $this->addSql('CREATE TABLE child (id INTEGER NOT NULL, parent_id INTEGER DEFAULT NULL, firstName VARCHAR(255) NOT NULL COLLATE BINARY, lastName VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_22B35429727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO child (id, parent_id, firstName, lastName) SELECT id, parent_id, firstName, lastName FROM __temp__child');
        $this->addSql('DROP TABLE __temp__child');
        $this->addSql('CREATE INDEX IDX_22B35429727ACA70 ON child (parent_id)');
        $this->addSql('DROP INDEX club_email_idx');
        $this->addSql('DROP INDEX IDX_8D93D64961190A32');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, club_id, username, firstName, lastName, phone, email, password, created_datetime, roles, new_user_code FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, username VARCHAR(255) NOT NULL COLLATE BINARY, firstName VARCHAR(255) NOT NULL COLLATE BINARY, lastName VARCHAR(255) NOT NULL COLLATE BINARY, phone VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, created_datetime DATETIME NOT NULL, new_user_code VARCHAR(255) DEFAULT NULL COLLATE BINARY, roles CLOB NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_8D93D64961190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, club_id, username, firstName, lastName, phone, email, password, created_datetime, roles, new_user_code) SELECT id, club_id, username, firstName, lastName, phone, email, password, created_datetime, roles, new_user_code FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX club_email_idx ON user (club_id, email)');
        $this->addSql('CREATE INDEX IDX_8D93D64961190A32 ON user (club_id)');
        $this->addSql('DROP INDEX IDX_99074648591CC992');
        $this->addSql('DROP INDEX IDX_99074648A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tutor AS SELECT id, user_id, course_id, is_substitute FROM tutor');
        $this->addSql('DROP TABLE tutor');
        $this->addSql('CREATE TABLE tutor (id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, course_id INTEGER DEFAULT NULL, is_substitute BOOLEAN DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_99074648A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_99074648591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tutor (id, user_id, course_id, is_substitute) SELECT id, user_id, course_id, is_substitute FROM __temp__tutor');
        $this->addSql('DROP TABLE __temp__tutor');
        $this->addSql('CREATE INDEX IDX_99074648591CC992 ON tutor (course_id)');
        $this->addSql('CREATE INDEX IDX_99074648A76ED395 ON tutor (user_id)');
        $this->addSql('DROP INDEX static_content_string_idx');
        $this->addSql('DROP INDEX IDX_6747643B211923AF');
        $this->addSql('DROP INDEX IDX_6747643B61190A32');
        $this->addSql('CREATE TEMPORARY TABLE __temp__static_content AS SELECT id, club_id, last_edited_by_user_id, id_string, content, last_edited FROM static_content');
        $this->addSql('DROP TABLE static_content');
        $this->addSql('CREATE TABLE static_content (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, last_edited_by_user_id INTEGER DEFAULT NULL, id_string VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, last_edited DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_6747643B61190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6747643B211923AF FOREIGN KEY (last_edited_by_user_id) REFERENCES user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO static_content (id, club_id, last_edited_by_user_id, id_string, content, last_edited) SELECT id, club_id, last_edited_by_user_id, id_string, content, last_edited FROM __temp__static_content');
        $this->addSql('DROP TABLE __temp__static_content');
        $this->addSql('CREATE UNIQUE INDEX static_content_string_idx ON static_content (id_string, club_id)');
        $this->addSql('CREATE INDEX IDX_6747643B211923AF ON static_content (last_edited_by_user_id)');
        $this->addSql('CREATE INDEX IDX_6747643B61190A32 ON static_content (club_id)');
        $this->addSql('DROP INDEX course_type_name_idx');
        $this->addSql('DROP INDEX IDX_447C8A2F3DA5256D');
        $this->addSql('DROP INDEX IDX_447C8A2F61190A32');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course_type AS SELECT id, club_id, image_id, name, description, challengeUrl, deleted, hide_on_homepage FROM course_type');
        $this->addSql('DROP TABLE course_type');
        $this->addSql('CREATE TABLE course_type (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, challengeUrl CLOB DEFAULT NULL COLLATE BINARY, deleted BOOLEAN DEFAULT NULL, hide_on_homepage BOOLEAN DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_447C8A2F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_447C8A2F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO course_type (id, club_id, image_id, name, description, challengeUrl, deleted, hide_on_homepage) SELECT id, club_id, image_id, name, description, challengeUrl, deleted, hide_on_homepage FROM __temp__course_type');
        $this->addSql('DROP TABLE __temp__course_type');
        $this->addSql('CREATE UNIQUE INDEX course_type_name_idx ON course_type (name)');
        $this->addSql('CREATE INDEX IDX_447C8A2F3DA5256D ON course_type (image_id)');
        $this->addSql('CREATE INDEX IDX_447C8A2F61190A32 ON course_type (club_id)');
        $this->addSql('DROP INDEX IDX_4E01E77591CC992');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course_class AS SELECT id, course_id, time, place FROM course_class');
        $this->addSql('DROP TABLE course_class');
        $this->addSql('CREATE TABLE course_class (id INTEGER NOT NULL, course_id INTEGER DEFAULT NULL, time DATETIME NOT NULL, place VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_4E01E77591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO course_class (id, course_id, time, place) SELECT id, course_id, time, place FROM __temp__course_class');
        $this->addSql('DROP TABLE __temp__course_class');
        $this->addSql('CREATE INDEX IDX_4E01E77591CC992 ON course_class (course_id)');
        $this->addSql('DROP INDEX IDX_B10172528D93D649');
        $this->addSql('CREATE TEMPORARY TABLE __temp__password_reset AS SELECT id, user, hashed_reset_code, reset_time FROM password_reset');
        $this->addSql('DROP TABLE password_reset');
        $this->addSql('CREATE TABLE password_reset (id INTEGER NOT NULL, user INTEGER DEFAULT NULL, hashed_reset_code VARCHAR(255) NOT NULL COLLATE BINARY, reset_time DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_B10172528D93D649 FOREIGN KEY (user) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO password_reset (id, user, hashed_reset_code, reset_time) SELECT id, user, hashed_reset_code, reset_time FROM __temp__password_reset');
        $this->addSql('DROP TABLE __temp__password_reset');
        $this->addSql('CREATE INDEX IDX_B10172528D93D649 ON password_reset (user)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP INDEX IDX_22B35429727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__child AS SELECT id, parent_id, firstName, lastName FROM child');
        $this->addSql('DROP TABLE child');
        $this->addSql('CREATE TABLE child (id INTEGER NOT NULL, parent_id INTEGER DEFAULT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO child (id, parent_id, firstName, lastName) SELECT id, parent_id, firstName, lastName FROM __temp__child');
        $this->addSql('DROP TABLE __temp__child');
        $this->addSql('CREATE INDEX IDX_22B35429727ACA70 ON child (parent_id)');
        $this->addSql('DROP INDEX IDX_169E6FB94A798B6F');
        $this->addSql('DROP INDEX IDX_169E6FB9CD8F897F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course AS SELECT id, semester_id, course_type_id, name, description, participant_limit, deleted FROM course');
        $this->addSql('DROP TABLE course');
        $this->addSql('CREATE TABLE course (id INTEGER NOT NULL, semester_id INTEGER DEFAULT NULL, course_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, participant_limit INTEGER NOT NULL, deleted BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO course (id, semester_id, course_type_id, name, description, participant_limit, deleted) SELECT id, semester_id, course_type_id, name, description, participant_limit, deleted FROM __temp__course');
        $this->addSql('DROP TABLE __temp__course');
        $this->addSql('CREATE INDEX IDX_169E6FB94A798B6F ON course (semester_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9CD8F897F ON course (course_type_id)');
        $this->addSql('DROP INDEX IDX_4E01E77591CC992');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course_class AS SELECT id, course_id, time, place FROM course_class');
        $this->addSql('DROP TABLE course_class');
        $this->addSql('CREATE TABLE course_class (id INTEGER NOT NULL, course_id INTEGER DEFAULT NULL, time DATETIME NOT NULL, place VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO course_class (id, course_id, time, place) SELECT id, course_id, time, place FROM __temp__course_class');
        $this->addSql('DROP TABLE __temp__course_class');
        $this->addSql('CREATE INDEX IDX_4E01E77591CC992 ON course_class (course_id)');
        $this->addSql('DROP INDEX IDX_B503165C591CC992');
        $this->addSql('DROP INDEX IDX_B503165CA76ED395');
        $this->addSql('DROP INDEX IDX_B503165CDD62C21B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course_queue_entity AS SELECT id, course_id, user_id, child_id FROM course_queue_entity');
        $this->addSql('DROP TABLE course_queue_entity');
        $this->addSql('CREATE TABLE course_queue_entity (id INTEGER NOT NULL, course_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, child_id INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO course_queue_entity (id, course_id, user_id, child_id) SELECT id, course_id, user_id, child_id FROM __temp__course_queue_entity');
        $this->addSql('DROP TABLE __temp__course_queue_entity');
        $this->addSql('CREATE INDEX IDX_B503165C591CC992 ON course_queue_entity (course_id)');
        $this->addSql('CREATE INDEX IDX_B503165CA76ED395 ON course_queue_entity (user_id)');
        $this->addSql('CREATE INDEX IDX_B503165CDD62C21B ON course_queue_entity (child_id)');
        $this->addSql('DROP INDEX IDX_447C8A2F61190A32');
        $this->addSql('DROP INDEX IDX_447C8A2F3DA5256D');
        $this->addSql('DROP INDEX course_type_name_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__course_type AS SELECT id, club_id, image_id, name, description, challengeUrl, deleted, hide_on_homepage FROM course_type');
        $this->addSql('DROP TABLE course_type');
        $this->addSql('CREATE TABLE course_type (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, challengeUrl CLOB DEFAULT NULL, deleted BOOLEAN DEFAULT NULL, hide_on_homepage BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO course_type (id, club_id, image_id, name, description, challengeUrl, deleted, hide_on_homepage) SELECT id, club_id, image_id, name, description, challengeUrl, deleted, hide_on_homepage FROM __temp__course_type');
        $this->addSql('DROP TABLE __temp__course_type');
        $this->addSql('CREATE INDEX IDX_447C8A2F61190A32 ON course_type (club_id)');
        $this->addSql('CREATE INDEX IDX_447C8A2F3DA5256D ON course_type (image_id)');
        $this->addSql('CREATE UNIQUE INDEX course_type_name_idx ON course_type (name)');
        $this->addSql('DROP INDEX IDX_C53D045F61190A32');
        $this->addSql('DROP INDEX club_name_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, club_id, file_name, file_path, name FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO image (id, club_id, file_name, file_path, name) SELECT id, club_id, file_name, file_path, name FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045F61190A32 ON image (club_id)');
        $this->addSql('CREATE UNIQUE INDEX club_name_idx ON image (club_id, name)');
        $this->addSql('DROP INDEX IDX_B6BD307F61190A32');
        $this->addSql('CREATE TEMPORARY TABLE __temp__message AS SELECT id, club_id, message, timestamp, expireDate FROM message');
        $this->addSql('DROP TABLE message');
        $this->addSql('CREATE TABLE message (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, message CLOB NOT NULL, timestamp DATETIME NOT NULL, expireDate DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO message (id, club_id, message, timestamp, expireDate) SELECT id, club_id, message, timestamp, expireDate FROM __temp__message');
        $this->addSql('DROP TABLE __temp__message');
        $this->addSql('CREATE INDEX IDX_B6BD307F61190A32 ON message (club_id)');
        $this->addSql('DROP INDEX IDX_D79F6B11DD62C21B');
        $this->addSql('DROP INDEX IDX_D79F6B11591CC992');
        $this->addSql('DROP INDEX IDX_D79F6B11A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__participant AS SELECT id, child_id, course_id, user_id, created_at FROM participant');
        $this->addSql('DROP TABLE participant');
        $this->addSql('CREATE TABLE participant (id INTEGER NOT NULL, child_id INTEGER DEFAULT NULL, course_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO participant (id, child_id, course_id, user_id, created_at) SELECT id, child_id, course_id, user_id, created_at FROM __temp__participant');
        $this->addSql('DROP TABLE __temp__participant');
        $this->addSql('CREATE INDEX IDX_D79F6B11DD62C21B ON participant (child_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11591CC992 ON participant (course_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11A76ED395 ON participant (user_id)');
        $this->addSql('DROP INDEX IDX_B10172528D93D649');
        $this->addSql('CREATE TEMPORARY TABLE __temp__password_reset AS SELECT id, user, hashed_reset_code, reset_time FROM password_reset');
        $this->addSql('DROP TABLE password_reset');
        $this->addSql('CREATE TABLE password_reset (id INTEGER NOT NULL, user INTEGER DEFAULT NULL, hashed_reset_code VARCHAR(255) NOT NULL, reset_time DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO password_reset (id, user, hashed_reset_code, reset_time) SELECT id, user, hashed_reset_code, reset_time FROM __temp__password_reset');
        $this->addSql('DROP TABLE __temp__password_reset');
        $this->addSql('CREATE INDEX IDX_B10172528D93D649 ON password_reset (user)');
        $this->addSql('DROP INDEX IDX_6747643B61190A32');
        $this->addSql('DROP INDEX IDX_6747643B211923AF');
        $this->addSql('DROP INDEX static_content_string_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__static_content AS SELECT id, club_id, last_edited_by_user_id, id_string, content, last_edited FROM static_content');
        $this->addSql('DROP TABLE static_content');
        $this->addSql('CREATE TABLE static_content (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, last_edited_by_user_id INTEGER DEFAULT NULL, id_string VARCHAR(255) NOT NULL, content CLOB NOT NULL, last_edited DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO static_content (id, club_id, last_edited_by_user_id, id_string, content, last_edited) SELECT id, club_id, last_edited_by_user_id, id_string, content, last_edited FROM __temp__static_content');
        $this->addSql('DROP TABLE __temp__static_content');
        $this->addSql('CREATE INDEX IDX_6747643B61190A32 ON static_content (club_id)');
        $this->addSql('CREATE INDEX IDX_6747643B211923AF ON static_content (last_edited_by_user_id)');
        $this->addSql('CREATE UNIQUE INDEX static_content_string_idx ON static_content (id_string, club_id)');
        $this->addSql('DROP INDEX IDX_99074648A76ED395');
        $this->addSql('DROP INDEX IDX_99074648591CC992');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tutor AS SELECT id, user_id, course_id, is_substitute FROM tutor');
        $this->addSql('DROP TABLE tutor');
        $this->addSql('CREATE TABLE tutor (id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, course_id INTEGER DEFAULT NULL, is_substitute BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO tutor (id, user_id, course_id, is_substitute) SELECT id, user_id, course_id, is_substitute FROM __temp__tutor');
        $this->addSql('DROP TABLE __temp__tutor');
        $this->addSql('CREATE INDEX IDX_99074648A76ED395 ON tutor (user_id)');
        $this->addSql('CREATE INDEX IDX_99074648591CC992 ON tutor (course_id)');
        $this->addSql('DROP INDEX IDX_8D93D64961190A32');
        $this->addSql('DROP INDEX club_email_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, club_id, username, firstName, lastName, phone, email, password, created_datetime, roles, new_user_code FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, club_id INTEGER DEFAULT NULL, username VARCHAR(255) NOT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_datetime DATETIME NOT NULL, new_user_code VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, club_id, username, firstName, lastName, phone, email, password, created_datetime, roles, new_user_code) SELECT id, club_id, username, firstName, lastName, phone, email, password, created_datetime, roles, new_user_code FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE INDEX IDX_8D93D64961190A32 ON user (club_id)');
        $this->addSql('CREATE UNIQUE INDEX club_email_idx ON user (club_id, email)');
    }
}
