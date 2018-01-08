<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\Services\SwiftMailer\Spool;

use Sil\Bundle\EmailBundle\Services\SwiftMailer\Spool\DbSpool as BaseDbSpool;
use Sil\Bundle\EmailBundle\Services\SwiftMailer\Spool\SpoolStatus;
use Sil\Bundle\EmailBundle\Services\Tracking;
use Sil\Bundle\EmailBundle\Services\InlineAttachments;
use Sil\Bundle\EmailBundle\Entity\EmailInterface;
use Sil\Bundle\EmailCRMBundle\Services\SwiftMailer\DecoratorPlugin\Replacements;
use Sil\Bundle\EmailCRMBundle\Services\AddressManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class DbSpool.
 */
class DbSpool extends BaseDbSpool
{
    /**
     * @var AddressManager
     */
    protected $addressManager;

    /**
     * @param Router        $router
     * @param EntityManager $manager
     * @param string        $environment
     */
    public function __construct(Router $router, EntityManager $manager, $environment, $addressManager)
    {
        $this->router = $router;
        $this->manager = $manager;
        $this->environment = $environment;
        $this->repository = $this->manager->getRepository(EmailInterface::class);
        $this->addressManager = $addressManager;
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param \Swift_Transport $transport         A transport instance
     * @param string[]         &$failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(\Swift_Transport $transport, &$failedRecipients = null)
    {
        $replacements = new Replacements($this->manager);
        $decorator = new \Swift_Plugins_DecoratorPlugin($replacements);

        $transport->registerPlugin($decorator);

        if (!$transport->isStarted()) {
            $transport->start();
        }

        $emails = $this->repository->findBy(
                array('status' => SpoolStatus::STATUS_READY, 'environment' => $this->environment), null
        );

        if (!count($emails)) {
            return 0;
        }

        $failedRecipients = (array) $failedRecipients;
        $count = 0;
        $time = time();

        foreach ($emails as $email) {
            $email->setStatus(SpoolStatus::STATUS_PROCESSING);

            $this->updateEmail($email);

            $message = unserialize(base64_decode($email->getMessage()));

            $addresses = $this->addressManager->manageAddresses($email);

            foreach ($addresses as $address => $name) {
                $message->setTo(trim($address));
                $content = $email->getContent();

                if ($email->getTracking()) {
                    $tracker = new Tracking($this->router);
                    $content = $tracker->addTracking($content, $address, $email->getId());
                }

                $attachmentsHandler = new InlineAttachments();
                $content = $attachmentsHandler->handle($content, $message);

                $message->setBody($content);

                try {
                    $count += $transport->send($message, $failedRecipients);
                    sleep($this->pauseTime);
                } catch (\Swift_TransportException $e) {
                    $email->setStatus(SpoolStatus::STATUS_READY);
                    $this->updateEmail($email);
                    // dump($e->getMessage());
                }
            }
            $email->setStatus(SpoolStatus::STATUS_COMPLETE);

            $this->updateEmail($email);

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }

    /**
     * @param AddressManager addressManager
     *
     * @return self
     */
    public function setAddressManager(AddressManager $addressManager)
    {
        $this->addressManager = $addressManager;

        return $this;
    }
}
