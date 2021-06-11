<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611142157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pet DROP INDEX UNIQ_E4529B85A8B4A30F, ADD INDEX IDX_E4529B85A8B4A30F (breed_id)');
        $this->addSql('ALTER TABLE pet DROP INDEX UNIQ_E4529B85C54C8C93, ADD INDEX IDX_E4529B85C54C8C93 (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pet DROP INDEX IDX_E4529B85A8B4A30F, ADD UNIQUE INDEX UNIQ_E4529B85A8B4A30F (breed_id)');
        $this->addSql('ALTER TABLE pet DROP INDEX IDX_E4529B85C54C8C93, ADD UNIQUE INDEX UNIQ_E4529B85C54C8C93 (type_id)');
    }
}
