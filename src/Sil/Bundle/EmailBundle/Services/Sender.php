<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Services;

use Doctrine\ORM\EntityManager;

class Sender
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var Tracking
     */
    protected $tracker;

    /**
     * @var InlineAttachments
     */
    protected $inlineAttachmentsHandler;

    /**
     * @var Swift_Mailer
     */
    protected $directMailer;

    /**
     * @var Swift_Mailer
     */
    protected $spoolMailer;

    /**
     * @var Email
     */
    protected $email;

    /**
     * @var array
     */
    protected $attachments;

    /**
     * @var bool Wheter the email has one or more recipients
     */
    protected $needsSpool;

    /**
     * @var AddressManager
     */
    protected $addressManager;

    /**
     * @param EntityManager     $manager
     * @param Tracking          $tracker
     * @param InlineAttachments $inlineAttachmentsHandler
     * @param Swift_Mailer      $directMailer
     * @param Swift_Mailer      $spoolMailer
     * @param AddressManager    $addressManager
     */
    public function __construct(EntityManager $manager, $tracker, $inlineAttachmentsHandler, $directMailer, $spoolMailer, $addressManager)
    {
        $this->manager = $manager;
        $this->tracker = $tracker;
        $this->inlineAttachmentsHandler = $inlineAttachmentsHandler;
        $this->directMailer = $directMailer;
        $this->spoolMailer = $spoolMailer;
        $this->addressManager = $addressManager;
    }

    /**
     * Sends an email.
     *
     * @param Email $email The email to send
     *
     * @return int Number of successfully sent emails
     */
    public function send($email)
    {
        $this->email = $email;
        $this->attachments = $email->getAttachments();
        $addresses = $this->addressManager->manageAddresses($this->email);

        $this->needsSpool = count($addresses) > 1;

        if ($this->email->getIsTest()) {
            $testAddressToArray = $this->email->getTestAddressAsArray();

            return $this->directSend($testAddressToArray);
        }
        if ($this->needsSpool) {
            return $this->spoolSend($addresses);
        } else {
            return $this->directSend($addresses);
        }
    }

    /**
     * Sends the mail directly.
     *
     * @param array $to               The To addresses
     * @param array $cc               The Cc addresses (optional)
     * @param array $bcc              The Bcc addresses (optional)
     * @param array $failedRecipients An array of failures by-reference (optional)
     *
     * @return int The number of successful recipients. Can be 0 which indicates failure
     */
    protected function directSend($to, &$failedRecipients = null, $message = null)
    {
        $message = $this->setupSwiftMessage($to, $message);

        $sent = $this->directMailer->send($message, $failedRecipients);
        $this->updateEmailEntity($message);

        return $sent;
    }

    /**
     * Spools the email.
     *
     * @param array $addresses
     */
    protected function spoolSend($addresses)
    {
        $message = $this->setupSwiftMessage($addresses);

        $this->updateEmailEntity($message);

        $sent = $this->spoolMailer->send($message);

        return $sent;
    }

    /**
     * Creates Swift_Message from Email.
     *
     * @param array  $to      The To addresses
     * @param string $message
     *
     * @return Swift_Message
     */
    protected function setupSwiftMessage($to, $message = null)
    {
        $content = $this->email->getContent();

        if ($message == null) {
            $message = new \Swift_Message(); //::newInstance();
        }

        if (!is_array($to)) {
            $to = [$to];
        }

        foreach ($to as $key => $address) {
            $to[$key] = trim($address);
        }

        // do not modify yet email content if it goes to spool
        if (!$this->needsSpool) {
            $content = $this->inlineAttachmentsHandler->handle($content, $message);

            if ($this->email->getTracking()) {
                try {
                    $content = $this->tracker->addTracking($content, $to[0], $this->email->getId());
                } catch (\Exception $e) {
                    die($e);
                }
            }
        }

        $message->setSubject($this->email->getFieldSubject())
                ->setFrom(trim($this->email->getFieldFrom()))
                ->setTo($to)
                ->setBody($content, 'text/html')
                ->addPart($this->email->getTextContent(), 'text/plain')
        ;

        if (!empty($cc = $this->email->getFieldCc())) {
            $message->setCc($cc);
        }

        if (!empty($bcc = $this->email->getFieldBcc())) {
            $message->setBcc($bcc);
        }

        $this->addAttachments($message);

        return $message;
    }

    /**
     * Adds attachments to the Swift_Message.
     *
     * @param Swift_Message $message
     */
    protected function addAttachments($message)
    {
        if (count($this->attachments) > 0) {
            foreach ($this->attachments as $file) {
                $attachment = (new \Swift_Attachment()) // ::newInstance()
                        ->setFilename($file->getName())
                        ->setContentType($file->getMimeType())
                        ->setBody($file->getFile())
                ;
                $message->attach($attachment);
            }
        }
    }

    /**
     * @param Swift_Message $message
     */
    protected function updateEmailEntity($message)
    {
        if ($this->needsSpool) {
            //set the id of the swift message so it can be retrieved from spool flushQueue()
            $this->email->setMessageId($message->getId());
        } elseif (!$this->email->getIsTest()) {
            $this->email->setSent(true);
        }

        $this->manager->persist($this->email);
        $this->manager->flush();
    }
}
