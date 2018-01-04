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
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Version20180103170539 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        /** @var Connection $connection */
        $connection = $this->container->get('doctrine')->getConnection();

        $payments = $connection->fetchAll('SELECT id, details FROM sil_ecommerce_payment');
        $originalValues = [];

        foreach ($payments as $payment) {
            $originalValues[] = [
                'id'      => $payment['id'],
                'details' => unserialize($payment['details']),
            ];
        }

        $this->addSql('ALTER TABLE sil_ecommerce_payment ADD details_new JSON NULL;');
        $this->addSql('COMMENT ON COLUMN sil_ecommerce_payment.details_new IS \'(DC2Type:json_array)\'');

        foreach ($originalValues as $originalValue) {
            $this->addSql('UPDATE sil_ecommerce_payment SET details_new = \'' . json_encode($originalValue['details']) . '\' WHERE id = \'' . $originalValue['id'] . '\';');
        }

        $this->addSql('ALTER TABLE sil_ecommerce_payment DROP details');
        $this->addSql('ALTER TABLE sil_ecommerce_payment RENAME details_new TO details');
        $this->addSql('ALTER TABLE sil_ecommerce_payment ALTER details SET NOT NULL');
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        /** @var Connection $connection */
        $connection = $this->container->get('doctrine')->getConnection();

        $payments = $connection->fetchAll('SELECT id, details FROM sil_ecommerce_payment');
        $originalValues = [];

        foreach ($payments as $payment) {
            $originalValues[] = [
                'id'      => $payment['id'],
                'details' => json_decode($payment['details']),
            ];
        }

        $this->addSql('ALTER TABLE sil_ecommerce_payment ADD details_new TEXT NULL;');
        $this->addSql('COMMENT ON COLUMN sil_ecommerce_payment.details_new IS \'(DC2Type:array)\'');

        foreach ($originalValues as $originalValue) {
            $this->addSql('UPDATE sil_ecommerce_payment SET details_new = \'' . serialize((array) $originalValue['details']) . '\' WHERE id = \'' . $originalValue['id'] . '\';');
        }

        $this->addSql('ALTER TABLE sil_ecommerce_payment DROP details');
        $this->addSql('ALTER TABLE sil_ecommerce_payment RENAME details_new TO details');
        $this->addSql('ALTER TABLE sil_ecommerce_payment ALTER details SET NOT NULL');
    }

    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
