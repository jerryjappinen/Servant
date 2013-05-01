<?php

/**
* Parser component
*
* Convert template formats into HTML, scripts into PHP, stylesheets into CSS
*
* Dependencies
*   - utilities()->load()
*/
class ServantParse extends ServantObject {

	/**
	* HAML to PHP
	*/
	public function hamlToPHP ($content) {
		$this->servant()->utilities()->load('mthaml');
		$haml = new MtHaml\Environment('php');
		return $haml->compileString($content, '');
	}

	/**
	* Jade to PHP
	*/
	public function jadeToPhp ($content) {
		$this->servant()->utilities()->load('jade');
		$jade = new Jade\Jade(true);
		return $jade->render($content);
	}

	/**
	* Markdown to HTML
	*/
	public function markdownToHtml ($content) {
		$this->servant()->utilities()->load('markdown');
		return Markdown($content);
	}

	/**
	* RST to HTML
	*
	* FLAG
	*   - parser is incomplete
	*/
	public function rstToHtml ($content) {
		$this->servant()->utilities()->load('rst');
		return RST($content);
	}

	/**
	* Textile to HTML
	*/
	public function textileToHtml ($content) {
		$this->servant()->utilities()->load('textile');
		$parser = new Textile();
		return $parser->textileThis($content);;
	}

	/**
	* Twig to HTML
	*/
	public function twigToHtml ($content) {
		$this->servant()->utilities()->load('twig');
		$twig = new Twig_Environment(new Twig_Loader_String());
		return $twig->render($content, array('servant' => $this->servant()));
	}

	/**
	* Plain text to HTML
	*/
	public function txtToHtml ($content) {
		return $this->markdownToHtml($content);
	}

	/**
	* Wiki markup to HTML
	*
	* FLAG
	*   - parser is incomplete
	*/
	public function wikiToHtml ($content) {
		$this->servant()->utilities()->load('wiky');
		$wiky = new wiky;
		$parsed = $wiky->parse($content);
		return $parsed ? $parsed : '';
	}

}

?>