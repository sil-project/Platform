<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Tests\Unit\Fixtures;

use Blast\Component\Resource\Repository\InMemoryRepository;
use Sil\Component\Emailing\Model\EmailAddress;
use Sil\Component\Emailing\Model\EmailAddressInterface;
use Sil\Component\Emailing\Model\GroupedMessage;
use Sil\Component\Emailing\Model\GroupedMessageInterface;
use Sil\Component\Emailing\Model\MailingList;
use Sil\Component\Emailing\Model\MailingListInterface;
use Sil\Component\Emailing\Model\Recipient;
use Sil\Component\Emailing\Model\RecipientInterface;
use Sil\Component\Emailing\Model\SimpleMessage;
use Sil\Component\Emailing\Model\SimpleMessageInterface;
use Sil\Component\Emailing\Repository\MailingListRepositoryInterface;
use Sil\Component\Emailing\Repository\RecipientRepositoryInterface;
use Sil\Component\Emailing\Repository\SimpleMessageRepositoryInterface;

class Fixtures
{
    /**
     * @var SimpleMessageRepositoryInterface
     */
    private $simpleMessageRepository;

    /**
     * @var GroupedMessageRepositoryInterface
     */
    private $groupedMessageRepository;

    /**
     * @var MailingListRepositoryInterface
     */
    private $mailingListRepository;

    /**
     * @var RecipientRepositoryInterface
     */
    private $recipientRepository;

    private $rawData = [];

    public function __construct()
    {
        $this->rawData = [
            'Mailing lists' => [
                'ListOne' => [
                    'name'        => 'ListOne',
                    'description' => null,
                    'recipients'  => [
                        'recipient1@sil.eu',
                        'recipient2@sil.eu',
                        'recipient3@sil.eu',
                        'recipient4@sil.eu',
                        'recipient5@sil.eu',
                    ],
                ],
                'ListTwo' => [
                    'name'        => 'ListTwo',
                    'description' => 'A small description of list',
                    'recipients'  => [
                        'recipient6@sil.eu',
                        'recipient7@sil.eu',
                        'recipient8@sil.eu',
                        'recipient9@sil.eu',
                        'recipient10@sil.eu',
                    ],
                ],
            ],
            'Simple messages' => [
                [
                    'title'    => 'Simple message One',
                    'content'  => 'A <b>rich</b> content without <a href="#">lorem</a>.',
                    'from'     => 'sender1@sil.eu',
                    'to'       => ['recipient1@sil.eu', 'recipient2@sil.eu'],
                    'cc'       => ['cc1@sil.eu', 'cc2@sil.eu', 'cc3@sil.eu', 'cc4@sil.eu'],
                    'bcc'      => ['bcc1@sil.eu'],
                ],
                [
                    'title'    => 'Simple message Two',
                    'content'  => 'A text content without lorem.',
                    'from'     => 'sender2@sil.eu',
                    'to'       => ['recipient3@sil.eu', 'recipient4@sil.eu'],
                    'cc'       => [],
                    'bcc'      => [],
                ],
            ],
            'Grouped messages' => [
                [
                    'title'   => 'Grouped message One',
                    'content' => 'A <b>rich</b> content without <a href="#">lorem</a>.',
                    'lists'   => [
                        'ListOne',
                    ],
                ],
                [
                    'title'   => 'Grouped message Two',
                    'content' => 'A text content without lorem.',
                    'lists'   => [
                        'ListOne',
                        'ListTwo',
                    ],
                ],
            ],
        ];

        $this->simpleMessageRepository = new InMemoryRepository(SimpleMessageInterface::class);
        $this->groupedMessageRepository = new InMemoryRepository(GroupedMessageInterface::class);
        $this->mailingListRepository = new InMemoryRepository(MailingListInterface::class);
        $this->recipientRepository = new InMemoryRepository(RecipientInterface::class);
        $this->emailAddressRepository = new InMemoryRepository(EmailAddressInterface::class);

        $this->generateFixtures();
    }

    private function generateFixtures(): void
    {
        $this->loadMailingLists();
        $this->loadSimpleMessages();
        $this->loadGroupedMessages();
    }

    private function loadMailingLists()
    {
        foreach ($this->rawData['Mailing lists'] as $mailingListData) {
            $mailingList = new MailingList($mailingListData['name']);

            foreach ($mailingListData['recipients'] as $recipientEmail) {
                $recipient = Recipient::createFromEmailAsString($recipientEmail);
                $this->recipientRepository->add($recipient);
                $mailingList->addRecipient($recipient);
            }

            $this->mailingListRepository->add($mailingList);
        }
    }

    private function loadSimpleMessages()
    {
        foreach ($this->rawData['Simple messages'] as $messageData) {
            $from = new EmailAddress($messageData['from']);
            $to = new EmailAddress($messageData['to'][0]);

            $this->emailAddressRepository->add($from);
            $this->emailAddressRepository->add($to);

            $simpleMessage = new SimpleMessage($messageData['title'], $messageData['content'], null, $from, $to);

            foreach ($messageData['to'] as $tos) {
                $toAddress = new EmailAddress($tos);
                if (!in_array($toAddress, $simpleMessage->getTo())) {
                    $simpleMessage->addTo($toAddress);
                    $this->emailAddressRepository->add($toAddress);
                }
            }

            foreach ($messageData['cc'] as $ccs) {
                $ccAddress = new EmailAddress($ccs);
                if (!in_array($ccAddress, $simpleMessage->getCc())) {
                    $simpleMessage->addCc($ccAddress);
                    $this->emailAddressRepository->add($ccAddress);
                }
            }

            foreach ($messageData['bcc'] as $bccs) {
                $bccAddress = new EmailAddress($bccs);
                if (!in_array($bccAddress, $simpleMessage->getBcc())) {
                    $simpleMessage->addBcc($bccAddress);
                    $this->emailAddressRepository->add($bccAddress);
                }
            }

            $this->simpleMessageRepository->add($simpleMessage);
        }
    }

    private function loadGroupedMessages()
    {
        foreach ($this->rawData['Grouped messages'] as $messageData) {
            $message = new GroupedMessage($messageData['title'], $messageData['content']);

            foreach ($messageData['lists'] as $listName) {
                $list = $this->mailingListRepository->findOneBy(['name' => $listName]);
                $message->addList($list);
            }

            $this->groupedMessageRepository->add($message);
        }
    }

    /**
     * @return SimpleMessageRepositoryInterface
     */
    public function getSimpleMessageRepository(): SimpleMessageRepositoryInterface
    {
        return $this->simpleMessageRepository;
    }

    /**
     * @return GroupedMessageRepositoryInterface
     */
    public function getGroupedMessageRepository(): GroupedMessageRepositoryInterface
    {
        return $this->groupedMessageRepository;
    }

    /**
     * @return MailingListRepositoryInterface
     */
    public function getMailingListRepository(): MailingListRepositoryInterface
    {
        return $this->mailingListRepository;
    }

    /**
     * @return RecipientRepositoryInterface
     */
    public function getRecipientRepository(): RecipientRepositoryInterface
    {
        return $this->recipientRepository;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}
