[![Latest Stable Version](https://poser.pugx.org/lucamauri/wiki2ban/v)](//packagist.org/packages/lucamauri/wiki2ban)
[![Total Downloads](https://poser.pugx.org/lucamauri/wiki2ban/downloads)](//packagist.org/packages/lucamauri/wiki2ban)
[![GPL v2 License](https://img.shields.io/badge/License-GPLv2-008033?logo=gpl)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
[![Built with Visual Studio Code](https://img.shields.io/badge/Built_with-VS_Code-007ACC?logo=visualstudiocode)](https://code.visualstudio.com)
[![StyleCI](https://github.styleci.io/repos/336330317/shield?branch=master)](https://github.styleci.io/repos/336330317?branch=master)

# Wiki2Ban

<img src="https://upload.wikimedia.org/wikipedia/commons/c/ce/W2BLogo.svg" width="256" align="left" />

Wiki2Ban (W2B) is a MediaWiki extension that logs failed authentication attempts to a file that [Fail2Ban](https://github.com/fail2ban/fail2ban) can read, enabling automatic IP banning of attackers.

This extension is inspired by [Extension:Fail2banlog](https://www.mediawiki.org/wiki/Extension:Fail2banlog), which is unmaintained and built for an older MediaWiki version. Wiki2Ban was written from scratch by [Luca Mauri](https://github.com/lucamauri), originally for [WikiTrek](https://github.com/WikiTrek), and released as open source for the broader MediaWiki community.

## Features

- Hooks into MediaWiki's authentication system to detect failed login attempts
- Writes a structured log line for each failure, including timestamp, username, wiki name, and client IP address
- Correctly resolves the client IP address behind reverse proxies and CDNs
- Log format is compatible with Fail2Ban out of the box
- Includes ready-to-use Fail2Ban filter and jail configuration files
- Includes an optional [Log Navigator](https://lnav.org/) format definition for interactive log analysis
- No database changes required
- Configurable log file path

## Requirements

- MediaWiki >= 1.35.0
- PHP >= 8.4
- [Fail2Ban](https://github.com/fail2ban/fail2ban) >= 0.10 (for progressive banning support)

## Installation

The easiest way to install the extension is via _Composer_, which will automatically resolve all dependencies.

Add the following to `composer.local.json` at the root of your MediaWiki installation (create the file if it does not exist):

```json
{
    "require": {
        "lucamauri/wiki2ban": "~1.1"
    },
    "extra": {
        "merge-plugin": {
            "include": []
        }
    }
}
```

Then run Composer from the root of your MediaWiki installation:

```bash
composer install --no-dev
```

Add the following line near the rest of the extension loading calls in `LocalSettings.php`:

```php
wfLoadExtension('Wiki2ban');
```

Then add the configuration parameters described in the next section.

## Configuration

Add the following to `LocalSettings.php`:

```php
$wgW2BLogFilePath = "/var/log/mediawiki/wiki2ban.log";
```

### `$wgW2BLogFilePath`

The full path to the log file that Wiki2Ban will write to and that Fail2Ban will monitor. The web server process must have write permission to this file and its parent directory.

Default value: `/var/log/mediawiki/wiki2ban.log`

## Fail2Ban configuration

After installing and configuring the extension, you need to configure Fail2Ban to monitor the log file. The `f2bconf/` directory in this repository contains ready-to-use configuration files.

### Filter

Copy `f2bconf/w2bfilter.conf` to Fail2Ban's filter directory:

```bash
cp f2bconf/w2bfilter.conf /etc/fail2ban/filter.d/w2bfilter.conf
```

### Jail rule

Copy `f2bconf/w2brule.conf` to Fail2Ban's jail directory:

```bash
cp f2bconf/w2brule.conf /etc/fail2ban/jail.d/wiki2ban.conf
```

Then edit the file to set `logpath` to match the value of `$wgW2BLogFilePath` in your `LocalSettings.php`.

### Tuning for production

The default rule triggers after 5 failed attempts in 60 seconds and bans for 10 minutes. For a production wiki exposed to the internet, consider stricter values:

```ini
maxretry = 3
findtime = 300
bantime  = 86400
```

This bans an IP for 24 hours after 3 failures within 5 minutes. Progressive banning is enabled by default in the provided configuration — each repeated offence doubles the ban duration up to a maximum of one week.

After making changes, reload Fail2Ban:

```bash
sudo systemctl reload fail2ban
```

## Troubleshooting

To capture detailed debug log messages from Wiki2Ban, add the following to `LocalSettings.php`:

```php
$wgShowExceptionDetails = true;
$wgDebugLogGroups['Wiki2Ban'] = "/var/log/mediawiki/Wiki2Ban-{$wgDBname}.log";
```

## Optional: Log Navigator format

`f2bconf/wiki2ban.json` is a format definition for the [Log Navigator](https://lnav.org/) application, which allows interactive exploration and filtering of the Wiki2Ban log file. See the [lnav format documentation](https://docs.lnav.org/en/latest/formats.html#defining-a-new-format) for installation instructions.

## License

[GNU General Public License, version 2 or later](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)

## Maintainers

[Luca Mauri](https://github.com/lucamauri)

## Contributors

[Luca Mauri](https://github.com/lucamauri)