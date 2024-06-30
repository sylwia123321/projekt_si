<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630144135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatars (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, filename VARCHAR(191) NOT NULL, UNIQUE INDEX UNIQ_B0C98520A76ED395 (user_id), UNIQUE INDEX uq_avatars_filename (filename), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, ingredients LONGTEXT NOT NULL, instructions LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(64) NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_A369E2B512469DE2 (category_id), INDEX IDX_A369E2B5F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes_tags (recipe_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_54E4F56F59D8A214 (recipe_id), INDEX IDX_54E4F56FBAD26311 (tag_id), PRIMARY KEY(recipe_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, slug VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatars ADD CONSTRAINT FK_B0C98520A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B5F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE recipes_tags ADD CONSTRAINT FK_54E4F56F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_tags ADD CONSTRAINT FK_54E4F56FBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatars DROP FOREIGN KEY FK_B0C98520A76ED395');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B512469DE2');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B5F675F31B');
        $this->addSql('ALTER TABLE recipes_tags DROP FOREIGN KEY FK_54E4F56F59D8A214');
        $this->addSql('ALTER TABLE recipes_tags DROP FOREIGN KEY FK_54E4F56FBAD26311');
        $this->addSql('DROP TABLE avatars');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE recipes');
        $this->addSql('DROP TABLE recipes_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
