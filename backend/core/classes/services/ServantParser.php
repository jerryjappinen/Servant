<?php

/**
* Parser service
*
* FLAG redundant
*
* DEPENDENCIES
*   ServantUtilities -> load
*/
class ServantParser extends ServantObject {

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

}

?>