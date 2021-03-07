[![StyleCI](https://github.styleci.io/repos/238323866/shield?branch=master)](https://github.styleci.io/repos/238323866)
[![Latest Stable Version](https://poser.pugx.org/lucamauri/page-to-github/v/stable)](https://packagist.org/packages/lucamauri/page-to-github)
[![Total Downloads](https://poser.pugx.org/lucamauri/page-to-github/downloads)](https://packagist.org/packages/lucamauri/page-to-github)
[![Latest Unstable Version](https://poser.pugx.org/lucamauri/page-to-github/v/unstable)](https://packagist.org/packages/lucamauri/page-to-github)
[![License](https://poser.pugx.org/lucamauri/page-to-github/license)](https://packagist.org/packages/lucamauri/page-to-github)
[![Monthly Downloads](https://poser.pugx.org/lucamauri/page-to-github/d/monthly)](https://packagist.org/packages/lucamauri/page-to-github)
[![Daily Downloads](https://poser.pugx.org/lucamauri/page-to-github/d/daily)](https://packagist.org/packages/lucamauri/page-to-github)
[![composer.lock](https://poser.pugx.org/lucamauri/page-to-github/composerlock)](https://packagist.org/packages/lucamauri/page-to-github)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/lucamauri/PageToGitHub.svg)](http://isitmaintained.com/project/lucamauri/PageToGitHub "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/lucamauri/PageToGitHub.svg)](http://isitmaintained.com/project/lucamauri/PageToGitHub "Percentage of issues still open")

# Wiki2Ban

<img align="left" width="64px" src="https://www.lucamauri.com/content/images/logo/Wiki2Ban.png">Wiki2Ban, W2B in short, is a MediaWiki extension to generate log of failed authentication that can be fed into [Fail2Ban](https://github.com/fail2ban/fail2ban) to block relevant IP addresses.
It was originally conceived and written by [Luca Mauri](https://github.com/lucamauri) for use in [Wikitrek](https://github.com/WikiTrek): it is released as open source here in case it can be useful to anybody else.

## Features

## Requirements

## Install

Easiest way to install the extension is using _Composer_: it will automatically resolve all the dependencies and install them as well.

Add the `require` configuration as in the following example to the `composer.local.json` at the root of your mediawiki installation, or create the file if it does not exist yet:

```JSON
{
    "require": {
        "lucamauri/wiki-2-ban": "~1.0"
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
$wgW2BlogFilePath = "/var/log/wiki2ban.log";
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

the content of this file can be copied into Fail2Ban's main configuration file (usually `/etc/fail2ban/jail.local`) or kept as a separate configuration file in `jail.d` directory.

### Filter

Filter is shown in the file:

```
/f2bconf/w2bfilter.conf
```

this file should be copied into Fail2Ban's filter directory (usually `/etc/fail2ban/filter.d/`).

## Troubleshoot

To read detailed logging messages, you can intercept the [log group](https://www.mediawiki.org/wiki/Manual:$wgDebugLogGroups) named `Wiki2Ban`: for instace with the following configuration into `LocalSetting.php`:

```php
$wgShowExceptionDetails = true;
$wgDebugLogGroups['Wiki2Ban'] = "/var/log/mediawiki/Wiki2Ban-{$wgDBname}.log";
```

## Documentation

### Notes

https://www.php.net/manual/en/datetime.format.php
ISO 8601 date

```PHP
$date = new DateTime('2000-01-01');
echo $date->format('c');
```

https://www.burlutsky.su/security/fail2ban-add-custom-rule/

```log
2004-02-12T15:19:21+00:00 MediaWiki login FAIL on WikiTrek from: 192.168.1.38
```

```PHP
$date .. " MediaWiki login FAIL on " .. $wgSitename .. " from: " .. $sourceIP
"$date MediaWiki login FAIL on $wgSitename from: $sourceIP\n"
```

https://regex101.com/r/i9RxRO/1/

```regex
(?P<timestamp>.*) MediaWiki login FAIL on (?P<wiki>.*) from: <HOST>
```

## License

[GNU General Public License, version 3](https://www.gnu.org/licenses/gpl-3.0.html)

## Maintainers

[Luca Mauri](https://github.com/lucamauri)

## Contributors

[Luca Mauri](https://github.com/lucamauri)
