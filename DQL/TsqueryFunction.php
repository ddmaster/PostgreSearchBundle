<?php
namespace Ddmaster\PostgreSearchBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * TsqueryFunction ::= "TSQUERY" "(" StringPrimary "," StringPrimary "[, " StringPrimary "])"
 */
class TsqueryFunction extends FunctionNode
{
    public $fieldName = null;
    public $queryString = null;
    public $configuration = null;
    
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->fieldName = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->queryString = $parser->StringPrimary();
        
        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->configuration = $parser->StringPrimary();
        }
        
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        if ($this->configuration) {
            $stmnt = $this->fieldName->dispatch($sqlWalker) .  " @@ to_tsquery(" . $this->configuration->dispatch($sqlWalker) . ", " . $this->queryString->dispatch($sqlWalker) . ")";
        }
        else {
            $stmnt = $this->fieldName->dispatch($sqlWalker) .  " @@ to_tsquery(" . $this->queryString->dispatch($sqlWalker) . ")";
        }
        
        return $stmnt;
        	
    }
}
