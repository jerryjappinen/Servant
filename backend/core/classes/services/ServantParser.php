<?php

/**
* Parser service
*
* Convert template formats into HTML, scripts into PHP, stylesheets into CSS
*
* DEPENDENCIES
*   ServantUtilities -> load
*/
class ServantParser extends ServantObject {



	/**
	* To CSS
	*/

	/**
	* LESS
	*/
	public function lessToCss ($less) {
		$this->servant()->utilities()->load('less');
		$parser = new lessc();

		// Don't compress in debug mode
		if (!$this->servant()->debug()) {
			$parser->setFormatter('compressed');
		}

		return $parser->parse($less);
	}

	/**
	* SCSS
	*/
	public function scssToCss ($scss) {
		$this->servant()->utilities()->load('scss');
		$parser = new scssc();

		// Don't compress in debug mode
		if (!$this->servant()->debug()) {
			$parser->setFormatter('scss_formatter_compressed');
		}

		return $parser->compile($scss);
	}



	/**
	* To PHP
	*/

	/**
	* HAML
	*/
	public function hamlToPhp ($haml) {
		$this->servant()->utilities()->load('mthaml');
		$parser = new MtHaml\Environment('php');
		return $parser->compileString($haml, '');
	}

	/**
	* Jade
	*/
	public function jadeToPhp ($jade) {
		$this->servant()->utilities()->load('jade');
		$parser = new Jade\Jade(true);
		return $parser->render($jade);
	}



	/**
	* To HTML
	*/

	/**
	* Markdown
	*/
	public function markdownToHtml ($markdown) {
		$this->servant()->utilities()->load('markdown');
		return Markdown($markdown);
	}

	/**
	* RST
	*
	* FLAG
	*   - parser is incomplete
	*/
	public function rstToHtml ($rst) {
		$this->servant()->utilities()->load('rst');
		return RST($rst);
	}

	/**
	* Textile
	*/
	public function textileToHtml ($textile) {
		$this->servant()->utilities()->load('textile');
		return create_object('Textile')->textileThis($textile);
	}

	/**
	* Twig to HTML
	*/
	public function twigToHtml ($twig, $scriptVariables = array()) {
		$this->servant()->utilities()->load('twig');
		return create_object('Twig_Environment', create_object('Twig_Loader_String'))->render($twig, $scriptVariables);
	}

	/**
	* Plain text to HTML
	*/
	public function txtToHtml ($txt) {
		return $this->markdownToHtml($txt);
	}

	/**
	* Wiki markup to HTML
	*
	* FLAG
	*   - parser is incomplete
	*/
	public function wikiToHtml ($wiki) {
		$this->servant()->utilities()->load('wiky');
		$parser = new wiky;
		$html = $parser->parse($wiki);
		return $html ? $html : '';
	}

}

?>