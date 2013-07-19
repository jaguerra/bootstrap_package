<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

# Include static TypoScript files from extensions using a hook.
# @see http://blog.causal.ch/2012/05/automatically-including-static-ts-from.html
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSources'][] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/TypoScriptTemplate.php:TYPO3\CMS\Speciality\Hooks\TypoScriptTemplate->preprocessIncludeStaticTypoScriptSources';


\TYPO3\CMS\Speciality\Hooks\TypoScriptTemplate::getInstance()->addStaticTemplates(array(
	'EXT:css_styled_content/static',
	'EXT:seo_basics/static',
	'EXT:news/Configuration/TypoScript',
	'EXT:form/Configuration/TypoScript',
	'EXT:fluidpages/Configuration/TypoScript',
	'EXT:fluidcontent/Configuration/TypoScript',
	'EXT:fluidcontent_bootstrap/Configuration/TypoScript',
	'EXT:speciality/Configuration/TypoScript', // keep loading last
));

# Development configuration (override default configuration)
# @todo adapt code for 6.2 after we have Context in the Core http://forge.typo3.org/issues/49988
# @todo adapt Readme.rst
//$developmentConfigurationFile = sprintf('%s/Configuration/%s/DefaultConfiguration.php',
//	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY),
//	\TYPO3\CMS\Speciality\Utility\Context::getInstance()->getName()
//);
//
//if (file_exists($developmentConfigurationFile)) {
//	include_once($developmentConfigurationFile);
//}
?>
