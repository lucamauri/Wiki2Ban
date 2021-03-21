[![StyleCI](https://github.styleci.io/repos/238323866/shield?branch=master)](https://github.styleci.io/repos/238323866)
[![Latest Stable Version](https://poser.pugx.org/lucamauri/wiki2ban/v/stable)](https://packagist.org/packages/lucamauri/wiki2ban)
[![Total Downloads](https://poser.pugx.org/lucamauri/wiki2ban/downloads)](https://packagist.org/packages/lucamauri/wiki2ban)
[![Latest Unstable Version](https://poser.pugx.org/lucamauri/wiki2ban/v/unstable)](https://packagist.org/packages/lucamauri/wiki2ban)
[![License](https://poser.pugx.org/lucamauri/wiki2ban/license)](https://packagist.org/packages/lucamauri/wiki2ban)
[![Monthly Downloads](https://poser.pugx.org/lucamauri/wiki2ban/d/monthly)](https://packagist.org/packages/lucamauri/wiki2ban)
[![Daily Downloads](https://poser.pugx.org/lucamauri/wiki2ban/d/daily)](https://packagist.org/packages/lucamauri/wiki2ban)
[![composer.lock](https://poser.pugx.org/lucamauri/wiki2ban/composerlock)](https://packagist.org/packages/lucamauri/wiki2ban)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/lucamauri/PageToGitHub.svg)](http://isitmaintained.com/project/lucamauri/wiki2ban "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/lucamauri/PageToGitHub.svg)](http://isitmaintained.com/project/lucamauri/wiki2ban "Percentage of issues still open")

# Wiki2Ban

<img src="https://upload.wikimedia.org/wikipedia/commons/c/ce/W2BLogo.svg" width="256" align="left" />Wiki2Ban, W2B in short, is a MediaWiki extension to generate log of failed authentication that can be fed into [Fail2Ban](https://github.com/fail2ban/fail2ban) to block relevant IP addresses.

This extension is inspired by [Extension Fail2Banlog](https://www.mediawiki.org/wiki/Extension:Fail2banlog), but this extension is unmantained and built for an old MediaWiki version.<br />
So this was written from scratch by written by [Luca Mauri](https://github.com/lucamauri) originally for use in [Wikitrek](https://github.com/WikiTrek): it is released as open source here in case it can be useful to anybody else.

## Features

## Requirements

## Installation

Easiest way to install the extension is using _Composer_: it will automatically resolve all the dependencies and install them as well.

Add the `require` configuration as in the following example to the `composer.local.json` at the root of your mediawiki installation, or create the file if it does not exist yet:

```JSON
{
    "require": {
        "lucamauri/wiki2ban": "~1.0"
    },
    "extra": {
        "merge-plugin": {
            "include": [
            ]
        }
    },
    "config": {
    }
}
```

and, in a command prompt, run Composer in the root of your mediawiki installation:

```
composer install --no-dev
```

Add the following code near the rest of the extensions loading in the site's `LocalSettings.php`:

```PHP
wfLoadExtension('Wiki2Ban');
```

Below this line, add the configuration parameters as explained below in _Configuration_ section.

## Configuration

In the `LocalSettigs.php` file add:

```
$wgW2BlogFilePath = "/var/log/mediawiki/wiki2ban.log";
```

### \$wgW2BlogFilePath

The path to the logfile the extension will write and that Fail2Ban will read to perform ban actons.

## Fail2Ban configuration

Finally you need to configure a rule and a filter on Fail2Ban: this extension contains two files in `f2bconf` direcory showing basic configuration.

### Rule

Rule is shown in the file:

```
/f2bconf/w2brule.conf
```

the content of this file can be copied into Fail2Ban's main configuration file (usually `/etc/fail2ban/jail.local`) or kept as a separate configuration file in `jail.d` directory. Remember to customize the parameter `logpath` with the path of the file defined in the configuration file (see above).

### Filter

Filter is shown in the file:

```
/f2bconf/w2bfilter.conf
```

this file should be copied into Fail2Ban's filter directory (usually `/etc/fail2ban/filter.d/`).

## Troubleshooting

To read detailed logging messages, you can intercept the [log group](https://www.mediawiki.org/wiki/Manual:$wgDebugLogGroups) named `Wiki2Ban`: for instace with the following configuration into `LocalSetting.php`:

```php
$wgShowExceptionDetails = true;
$wgDebugLogGroups['Wiki2Ban'] = "/var/log/mediawiki/Wiki2Ban-{$wgDBname}.log";
```

## Additional file

`wiki2ban.json` contained in `f2bconf` folder is a definition for _Log Navigator_ application as explained here: https://docs.lnav.org/en/latest/formats.html#defining-a-new-format

## License

[GNU General Public License, version 3](https://www.gnu.org/licenses/gpl-3.0.html)

## Maintainers

[Luca Mauri](https://github.com/lucamauri)

## Contributors

[Luca Mauri](https://github.com/lucamauri)
