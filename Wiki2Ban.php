<?php
/**
 * Wiki2Ban — MediaWiki extension to log failed login attempts for Fail2Ban.
 *
 * @file
 * @license GPL-2.0-or-later
 */

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Config\MainConfig;
use RequestContext;
use User;

class Wiki2BanHooks {

    /** @var MainConfig */
    private MainConfig $config;

    /**
     * Constructor — receives services injected by MediaWiki's service container.
     *
     * @param MainConfig $config The main MediaWiki configuration object
     */
    public function __construct( MainConfig $config ) {
        $this->config = $config;
    }

    /**
     * Hook handler for login auditing.
     * Fires on every login attempt and writes a log line for failed attempts.
     *
     * @see https://www.mediawiki.org/wiki/Manual:Hooks/AuthManagerLoginAuthenticateAudit
     *
     * @param AuthenticationResponse $response The authentication result
     * @param User|null              $user     User object on successful auth, null on failure
     * @param string|null            $username Username supplied in the login attempt
     */
    public function onAuthManagerLoginAuthenticateAudit(
        AuthenticationResponse $response,
        ?User $user,
        ?string $username
    ): void {
        $siteName = $this->config->get( 'Sitename' );
        $logFilePath = $this->config->get( 'W2BLogFilePath' );
        $defaultLogFilePath = '/var/log/mediawiki/wiki2ban.log';

        if ( $logFilePath === null || $logFilePath === '' ) {
            wfDebugLog( 'Wiki2Ban', 'Unable to read W2BLogFilePath parameter value. Defaulting to: ' . $defaultLogFilePath );
            $logFilePath = $defaultLogFilePath;
        }

        if ( $response->status === 'FAIL' ) {
            $now = new DateTime( 'NOW' );
            $logTimeStamp = $now->format( 'c' );
            wfDebugLog( 'Wiki2Ban', 'TimeStamp is: ' . $logTimeStamp );

            $clientIP = RequestContext::getMain()->getRequest()->getIP();
            wfDebugLog( 'Wiki2Ban', 'IP address is: ' . $clientIP );

            $safeUsername = $username ?? '(unknown)';

            if ( !error_log( "$logTimeStamp MediaWiki login FAIL for $safeUsername on $siteName from: $clientIP\n", 3, $logFilePath ) ) {
                wfDebugLog( 'Wiki2Ban', 'Unable to write to logfile: ' . $logFilePath );
            }
        }
    }
}