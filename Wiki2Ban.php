<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
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
