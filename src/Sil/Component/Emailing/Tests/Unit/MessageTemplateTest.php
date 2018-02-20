<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Tests\Unit;

use DateTime;
use PHPUnit\Framework\TestCase;
use Sil\Component\Emailing\Model\ContentTokenDataType;
use Sil\Component\Emailing\Model\EmailAddress;
use Sil\Component\Emailing\Model\SimpleMessage;
use Sil\Component\Emailing\Service\TemplateHandler;
use Sil\Component\Emailing\Tests\Unit\Fixtures\Fixtures;

class MessageTemplateTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_applying_template_with_template_handler_to_a_new_message()
    {
        $from = new EmailAddress('from@sil.eu');
        $to = new EmailAddress('to@sil.eu');

        $templateData = $this->fixtures->getRawData()['Templates']['rich'];
        $template = $this->fixtures->getMessageTemplateRepository()->findOneBy(['name' => $templateData['name']]);

        $message = new SimpleMessage('Simple message', 'a simple message test', $from, $to);

        $templateHandler = new TemplateHandler();
        $templateHandler->applyTemplateToMessage($template, $message);

        $this->assertEquals(count($template->getTokenTypes()), count($message->getTokens()));

        foreach ($message->getTokens() as $token) {
            $this->assertContains($token->getTokenType(), $template->getTokenTypes());
        }
    }

    public function test_clearing_message_tokens()
    {
        $messageData = $this->fixtures->getRawData()['Simple messages'][0];
        $message = $this->fixtures->getSimpleMessageRepository()->findOneBy(['title' => $messageData['title']]);

        $this->assertEquals(6, count($message->getTokens()));

        $message->clearTokens();

        $this->assertEquals(0, count($message->getTokens()));
    }

    public function test_replacing_tokens_on_message()
    {
        $messageData = $this->fixtures->getRawData()['Simple messages'][0];
        $templateData = $this->fixtures->getRawData()['Templates'][$messageData['template']];
        $message = $this->fixtures->getSimpleMessageRepository()->findOneBy(['title' => $messageData['title']]);

        $tokenValues = [
            ContentTokenDataType::TYPE_BOOLEAN  => true,
            ContentTokenDataType::TYPE_DATE     => new DateTime('2020-05-01'),
            ContentTokenDataType::TYPE_DATETIME => new DateTime('2020-05-01 12:12:42'),
            ContentTokenDataType::TYPE_STRING   => 'a replaced string',
            ContentTokenDataType::TYPE_INTEGER  => 42,
            ContentTokenDataType::TYPE_FLOAT    => 512.42,
        ];

        foreach ($message->getTokens() as $token) {
            $token->setValue($tokenValues[$token->getTokenType()->getDataType()->getValue()]);
        }

        $this->assertEquals(trim($templateData['content']), $message->getContent());

        $templateHandler = new TemplateHandler();
        $templateHandler->replaceTokensOfMessage($message);

        $expectedContent = trim('A template with token placeholders :
                    - Yes
                    - a replaced string
                    - 42
                    - 512.42
                    - 2020-05-01
                    - 2020-05-01 12:12:42
        ');

        $this->assertEquals($expectedContent, $message->getContent());
    }
}
