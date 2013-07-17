<?php
namespace TYPO3\CMS\Speciality\ViewHelpers;
/***************************************************************
*  Copyright notice
*
*  (c) 2012
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * View helper which returns info related to language.
 *
 * {namespace s=TYPO3\CMS\Speciality\ViewHelpers}
 *
 * Short hand syntax
 * =================
 *
 * <f:if condition="{s:language()} == 1">
 * foo
 * </f:if>
 *
 * <f:if condition="{0: {s:language(format: 'code')}} == {0: en}">
 * foo
 * </f:if>
 *
 *
 * Extended syntax
 * =================
 *
 * <s:language /> : display language uid
 * <s:language format="code" /> : display language code
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  media
 * @author      Fabien Udriot <fabien.udriot@typo3.org>
 */
class LanguageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns info related to language.
	 *
	 * @param string $format: possible values: code
	 * @return string
	 */
	public function render($format = '') {
		$result = $GLOBALS['TSFE']->sys_language_uid;
		if ($format === 'code') {
			$result = $this->getLanguageCode();
		}
		return $result;
	}

	/**
	 * Get the current language
	 */
	protected function getLanguageCode() {
		$result = 'en';
		if (isset($GLOBALS['TSFE']->config['config']['language'])) {
			$result = $GLOBALS['TSFE']->config['config']['language'];
		}
		return $result;
	}
}

?>