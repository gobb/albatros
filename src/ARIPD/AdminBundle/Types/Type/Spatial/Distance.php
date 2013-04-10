<?php
namespace ARIPD\DefaultBundle\Types\Type\Spatial;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * DQL function for calculating distances between two points
 *
 * Example: DISTANCE(foo.point, POINT_STR(:param))
 * 
 * SELECT username, X(coordinate), Y(coordinate), (GLength(LineStringFromWKB(LineString(coordinate, GeomFromText('POINT(40.993974 29.12585)'))))) AS distance FROM user ORDER BY distance ASC
 * 
 */
class Distance extends FunctionNode {
	private $firstArg;
	private $secondArg;

	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
		//Need to do this hacky linestring length thing because
		//despite what MySQL manual claims, DISTANCE isn't actually implemented...
		return 'GLength(LineString(' . $this->firstArg->dispatch($sqlWalker)
				. ', ' . $this->secondArg->dispatch($sqlWalker) . '))';
	}

	public function parse(\Doctrine\ORM\Query\Parser $parser) {
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->firstArg = $parser->ArithmeticPrimary();
		$parser->match(Lexer::T_COMMA);
		$this->secondArg = $parser->ArithmeticPrimary();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}
}
