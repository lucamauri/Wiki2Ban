<?php
use MediaWiki\MediaWikiServices;

class PageToGitHubSpecial extends SpecialPage {
	function __construct() {
		parent::__construct('PageToGitHub');
	}

	function execute( $par ) {
        $variablesNames = ["P2GNameSpace", "P2GKeyword", "P2GAddKeyword", "P2GIgnoreMinor","P2GAuthToken", "P2GOwner", "P2GRepo"];
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		# Do stuff
        $config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig('PageToGitHub');
        
        // https://doc.wikimedia.org/mediawiki-core/master/php/classOutputPage.html
        $output->addWikiMsg('p2g-specialpage-text');
        $output->addWikiMsg('p2g-specialpage-config-title');
        $output->addWikiMsg('p2g-specialpage-variables-text');

        $output->addHTML('<table class="wikitable plainlinks" id="p2g-variables"><tbody>
            <tr>
            <th>Variabile</th>
            <th>Valore</th>
            </tr>');

        foreach ($variablesNames as $variableName) {
            ${$variableName} = $config->get($variableName);
            $output->addHTML('<tr>');
            $output->addHTML('<th>' . $variableName . '</th>');
            if ($variableName == 'P2GAuthToken') {
                $variableContent = "''hidden''";
            } else {
                $variableContent = "<code>" . ${$variableName} . "</code>";
            }
            $output->addHTML('<td>');
            $output->addWikiTextAsContent($variableContent);
            $output->addHTML('</td>');
            $output->addHTML('</tr>');
        }
        
        $output->addHTML('</tbody></table>');

        //$wikitext = 'Hello world!';
		//$output->addWikiTextAsInterface( $wikitext );
	}
}