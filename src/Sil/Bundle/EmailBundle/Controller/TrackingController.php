<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sil\Bundle\EmailBundle\Entity\EmailReceipt;
use Sil\Bundle\EmailBundle\Entity\EmailLink;

class TrackingController extends Controller
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * Keep track of email openings.
     *
     * @param string $emailId
     * @param string $recipient
     *
     * @return Response
     */
    public function trackOpensAction($emailId, $recipient)
    {
        $recipient = base64_decode($recipient);

        $this->trackOpens($emailId, $recipient);

        return new Response('', 200);
    }

    /**
     * Keep track of followed email links.
     *
     * @param string $emailId
     * @param string $recipient
     * @param string $destination
     *
     * @return RedirectResponse
     */
    public function trackLinksAction($emailId, $recipient, $destination)
    {
        $dest = base64_decode($destination);
        $recipient = base64_decode($recipient);

        //if the email has no delivery confirmation the link click is one
        $this->trackOpens($emailId, $recipient);

        $this->trackLinks($emailId, $recipient, $dest);

        return new RedirectResponse($dest, 302);
    }

    /**
     * @param string $emailId
     * @param string $recipient
     */
    public function trackOpens($emailId, $recipient)
    {
        $count = 0;

        $this->initManager();

        $email = $this->manager->getRepository('SilEmailBundle:Email')->find($emailId);

        if (!$email) {
            return;
        }

        $receipts = $email->getReceipts();

        if ($receipts->count() > 0) {
            foreach ($receipts->getSnapshot() as $receipt) {
                if ($receipt->getAddress() == $recipient) {
                    ++$count;
                }
            }
        }
        if ($count == 0) {
            $this->addReceipt($email, $recipient);
        }
    }

    /**
     * @param string $emailId
     * @param string $recipient
     * @param string $destination
     */
    private function trackLinks($emailId, $recipient, $destination)
    {
        $count = 0;

        $this->initManager();

        $email = $this->manager->getRepository('SilEmailBundle:Email')->find($emailId);

        if (!$email) {
            return;
        }

        $links = $email->getLinks();

        if ($links->count() > 0) {
            foreach ($links->getSnapshot() as $link) {
                if ($link->getAddress() == $recipient && $link->getDestination() == $destination) {
                    ++$count;
                }
            }
        }
        if ($count == 0) {
            $this->addLink($email, $recipient, $destination);
        }
    }

    /**
     * Creates and persists the EmailReceipt entity.
     *
     * @param Email  $email
     * @param string $recipient
     */
    private function addReceipt($email, $recipient)
    {
        $newReceipt = new EmailReceipt();
        $newReceipt->setEmail($email);
        $newReceipt->setAddress($recipient);
        $newReceipt->setDate(new \DateTime());

        $email->addReceipt($newReceipt);

        $this->manager->persist($email);
        $this->manager->flush();
    }

    /**
     * Creates and persists the EmailLink entity.
     *
     * @param type $email
     * @param type $recipient
     * @param type $destination
     */
    private function addLink($email, $recipient, $destination)
    {
        $newLink = new EmailLink();
        $newLink->setEmail($email);
        $newLink->setAddress($recipient);
        $newLink->setDate(new \DateTime());
        $newLink->setDestination($destination);
        $email->addLink($newLink);

        $this->manager->persist($email);
        $this->manager->flush();
    }

    private function initManager()
    {
        if (!$this->manager) {
            $this->manager = $this->getDoctrine()->getManager();
        }
    }
}
