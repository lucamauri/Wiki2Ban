<?php
use MediaWiki\MediaWikiServices;

class PageToGitHubCommon {
	function __construct() {
        parent::__construct('PageToGitHub');
        
        $variablesNames = ["P2GNameSpace", "P2GKeyword", "P2GAddKeyword", "P2GIgnoreMinor","P2GAuthToken", "P2GOwner", "P2GRepo"];
        $config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('PageToGitHub');
        foreach ($variablesNames as $variableName) {
            ${$variableName} = $config->get($variableName);            
        }
    }	
}