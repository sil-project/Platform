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

namespace Blast\DoctrinePgsqlBundle\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Pattern matching function
 * Usage: SUBSTRING(field, regexp)
 * Outputs: SUBSTRING(field FROM regexp).
 */
class Substring extends FunctionNode
{
    protected $field;
    protected $regexpExpression;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->regexpExpression = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->valueExpression = $parser->StringExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'SUBSTRING(' . $this->field->dispatch($sqlWalker) . ' FROM ' .
            $sqlWalker->walkStringPrimary($this->regexpExpression) . ')';
    }
}
