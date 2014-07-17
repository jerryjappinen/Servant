<?php

/**
* 
*/
class ServantTestMain extends ServantTest {

	function testMainHasAssetsGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'assets');
	}
	function testMainHasAvailableGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'available');
	}
	function testMainHasConstantsGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'constants');
	}
	function testMainHasCreator ($servant) {
		return $this->shouldHaveMethod($servant, 'create');
	}
	function testMainHasDebugGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'debug');
	}
	function testMainHasFilesGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'files');
	}
	function testMainHasManifestGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'manifest');
	}
	function testMainHasPathsGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'paths');
	}
	function testMainHasSitemapGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'sitemap');
	}
	function testMainHasUtilitiesGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'utilities');
	}
	function testMainHasVersionGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'version');
	}
	function testMainHasWarningsGetter ($servant) {
		return $this->shouldHaveMethod($servant, 'warnings');
	}

}

?>