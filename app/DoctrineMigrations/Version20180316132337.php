<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180316132337 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, isbn VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, publicationDate DATETIME NOT NULL, UNIQUE INDEX UNIQ_CBE5A331CC1CF4E6 (isbn), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, book_id INT DEFAULT NULL, rating SMALLINT NOT NULL, body LONGTEXT NOT NULL, publicationDate DATETIME NOT NULL, UNIQUE INDEX UNIQ_794381C6F675F31B (author_id), INDEX IDX_794381C616A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authors_books (author_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_2DFDA3CBF675F31B (author_id), INDEX IDX_2DFDA3CB16A2B381 (book_id), PRIMARY KEY(author_id, book_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C616A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CBF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CB16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C616A2B381');
        $this->addSql('ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CB16A2B381');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CBF675F31B');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE authors_books');
    }
}
