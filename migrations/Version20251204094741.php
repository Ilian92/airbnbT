<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251204094741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE property_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('CREATE TABLE author (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, age INT NOT NULL, biography VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE book (id SERIAL NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
        $this->addSql('CREATE TABLE book_genre (book_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(book_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_8D92268116A2B381 ON book_genre (book_id)');
        $this->addSql('CREATE INDEX IDX_8D9226814296D31F ON book_genre (genre_id)');
        $this->addSql('CREATE TABLE discussion (id SERIAL NOT NULL, book_id INT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C0B9F90F16A2B381 ON discussion (book_id)');
        $this->addSql('CREATE TABLE genre (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D92268116A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D9226814296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT fk_e00cedde549213ec');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT fk_e00cedde9a4aa658');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c63301c60');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c6549213ec');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c69a4aa658');
        $this->addSql('ALTER TABLE property DROP CONSTRAINT fk_8bf21cde1fb8d185');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE property');
        $this->addSql('ALTER TABLE "user" DROP first_name');
        $this->addSql('ALTER TABLE "user" DROP last_name');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE "user" DROP address');
        $this->addSql('ALTER TABLE "user" DROP city');
        $this->addSql('ALTER TABLE "user" DROP postal_code');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE property_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id SERIAL NOT NULL, property_id INT NOT NULL, guest_id INT NOT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, total_price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e00cedde549213ec ON booking (property_id)');
        $this->addSql('CREATE INDEX idx_e00cedde9a4aa658 ON booking (guest_id)');
        $this->addSql('COMMENT ON COLUMN booking.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE review (id SERIAL NOT NULL, property_id INT NOT NULL, guest_id INT NOT NULL, booking_id INT NOT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_794381c6549213ec ON review (property_id)');
        $this->addSql('CREATE INDEX idx_794381c69a4aa658 ON review (guest_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_794381c63301c60 ON review (booking_id)');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE property (id SERIAL NOT NULL, host_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, price_per_night DOUBLE PRECISION NOT NULL, max_guests INT NOT NULL, bedrooms INT DEFAULT NULL, bathrooms INT DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, note INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_8bf21cde1fb8d185 ON property (host_id)');
        $this->addSql('COMMENT ON COLUMN property.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT fk_e00cedde549213ec FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT fk_e00cedde9a4aa658 FOREIGN KEY (guest_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c63301c60 FOREIGN KEY (booking_id) REFERENCES booking (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c6549213ec FOREIGN KEY (property_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c69a4aa658 FOREIGN KEY (guest_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT fk_8bf21cde1fb8d185 FOREIGN KEY (host_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book_genre DROP CONSTRAINT FK_8D92268116A2B381');
        $this->addSql('ALTER TABLE book_genre DROP CONSTRAINT FK_8D9226814296D31F');
        $this->addSql('ALTER TABLE discussion DROP CONSTRAINT FK_C0B9F90F16A2B381');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_genre');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL');
        $this->addSql('ALTER TABLE "user" ADD first_name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD last_name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD postal_code INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
