<?php
/**
 * Wiki2Ban — MediaWiki extension to log failed login attempts for Fail2Ban.
 *
 * @file
 * @license GPL-2.0-or-later
 */

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\MediaWikiServices;
use RequestContext;
use User;

class Wiki2BanHooks
{
    /**
     * Hook for login auditing
     * https://www.mediawiki.org/wiki/Manual:Hooks/AuthManagerLoginAuthenticateAudit.
     *
     * @param AuthenticationResponse $response Is login successful?
     * @param User|null              $user     User object on successful auth
     * @param string                 $username Username for failed attempts.
     **/
    public static function onAuthManagerLoginAuthenticateAudit(
        AuthenticationResponse $response,
        ?User $user,
        ?string $username
    ): void {
        $config = MediaWikiServices::getInstance()->getMainConfig();
        $siteName = $config->get('Sitename');
        $logFilePath = $config->get('W2BLogFilePath');
        $defaultLogFilePath = '/var/log/mediawiki/wiki2ban.log';

        if ( $logFilePath === null || $logFilePath === '' ) {
            wfDebugLog('Wiki2Ban', 'Unable to read W2BLogFilePath parameter value. Defaulting to: '.$defaultLogFilePath);
            $logFilePath = $defaultLogFilePath;
        }

        if ($response->status == 'FAIL') {
            $now = new DateTime('NOW');
            $logTimeStamp = $now->format('c');
            wfDebugLog('Wiki2Ban', 'TimeStamp is: '.$logTimeStamp);

            $clientIP = RequestContext::getMain()->getRequest()->getIP();
            wfDebugLog('Wiki2Ban', 'IP address is: '.$clientIP);

            $safeUsername = $username ?? '(unknown)';

            if (!error_log("$logTimeStamp MediaWiki login FAIL for $safeUsername on $siteName from: $clientIP\n", 3, $logFilePath)) {
                wfDebugLog('Wiki2Ban', 'Unable to write to logfile: '.$logFilePath);
            }
        }
    }
}
