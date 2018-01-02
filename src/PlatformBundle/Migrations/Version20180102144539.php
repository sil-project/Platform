<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180102144539 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE sil_crm_city ADD insee_code VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE sil_crm_city ADD lat DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE sil_crm_city ADD lng DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE sil_crm_city DROP insee_code');
        $this->addSql('ALTER TABLE sil_crm_city DROP lat');
        $this->addSql('ALTER TABLE sil_crm_city DROP lng');
    }

    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
