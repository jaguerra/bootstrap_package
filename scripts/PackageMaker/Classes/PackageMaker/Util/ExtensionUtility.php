<?php

namespace PackageMaker\Util;

class ExtensionUtility {

	/**
	 * @var array
	 */
	protected $extensions = array(
		'fluidcontent',
		'fluidcontent_bootstrap',
		'fluidpages',
		'flux',
		'introduction',
		'jquerycolorbox',
		'media',
		'metadata',
		'news',
		'realurl',
		'seo_basics',
		'speciality',
		'nc_staticfilecache',
		'vhs',
		'infinite_scroll_gallery',
		'wt_spamshield'
	);

	/**
	 * @return array
	 */
	public function getExtensionToPackage() {
		return $this->extensions;
	}

	/**
	 * @return array
	 */
	public function getExtensionsToGitUpdate() {
		$extensions = $this->extensions;

		$blackList = array('introduction', 'speciality', 'wt_spamshield', 'jquerycolorbox');
		foreach ($blackList as $element) {
			$key = array_search($element, $extensions);
			unset($extensions[$key]);

		}
		return $extensions;
	}

	/**
	 * @return array
	 */
	public function getExtensions() {
		return $this->extensions;
	}

}

