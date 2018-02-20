<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Service;

use Sil\Component\Emailing\Model\ContentToken;
use Sil\Component\Emailing\Model\MessageInterface;
use Sil\Component\Emailing\Model\MessageTemplateInterface;

class TemplateHandler
{
    /**
     * Applies token to the message according to template token types.
     *
     * @param MessageTemplateInterface $template
     * @param MessageInterface         $message
     */
    public function applyTemplateToMessage(MessageTemplateInterface $template, MessageInterface $message): void
    {
        $message->clearTokens();
        $message->setTemplate($template);
        $message->setContent($template->getContent());

        foreach ($template->getTokenTypes() as $tokenType) {
            $token = new ContentToken($message, $tokenType);
            $message->addToken($token);
        }
    }

    /**
     * Replaces all token placeholder with values (if they are set) in message content.
     *
     * @param MessageInterface $message
     */
    public function replaceTokensOfMessage(MessageInterface $message): void
    {
        foreach ($message->getTokens() as $token) {
            if ($token->getValue() === null) {
                continue;
            }

            $rawContent = $message->getContent();

            $regexExpression = sprintf('/%s/', preg_quote('%%' . $token->getName() . '%%'));

            $rawContent = preg_replace($regexExpression, $token->getValueAsString(), $rawContent);

            $message->setContent($rawContent);
        }
    }
}
