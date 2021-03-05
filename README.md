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

## Notes

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

## Features

## Requirements

## Install

Easiest way to install the extension is using _Composer_: it will automatically resolve all the dependencies and install them as well.

Add the `require` configuration as in the following example to the `composer.local.json` at the root of your mediawiki installation, or create the file if it does not exist yet:

```JSON
{
    "require": {
        "lucamauri/page-to-github": "~1.0"
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
wfLoadExtension('PageToGitHub');
```

Below this line, add the configuration parameters as explained below in _Configuration_ section.

## Configuration

In the `LocalSettigs.php` file add:

```
$wgP2GAuthToken = 'GitHub-Token';
$wgP2GIgnoreMinor = true;
$wgP2GKeyword = 'Keyword';
$wgP2GAddKeyword = true;
$wgP2GNameSpace = 'Module';
$wgP2GOwner = 'Project-Or-Person';
$wgP2GRepo = 'Name-Of-Your-Repository';
```

### \$wgP2GAuthToken

The GitHub token needed to authenticate and made modification the the repository. You can generate one in your GitHub account in _Settings_ > _Developer settings_ > _Personal access tokens_

### \$wgP2GIgnoreMinor

If empty or set as `true` the revision is not pushed to GitHub if is marked as _Minor_

### \$wgP2GKeyword

An optional keyword to check into the page. When present, P2G will _not_ upload pages if the keyword is not written in the page. If the parameter is omitted, P2G will upload all pages in the Namespace specified above.

### \$wgP2GAddKeyword

An optional boolean parameter: when set to `true` the word defined in _\$wgP2GKeyword_ is added before the name of the page to form the filename.

### \$wgP2GNameSpace

P2G will upload pages only belonging to the namespace spedified in this variable

### \$wgP2GOwner

The Person or Organization owner of the repository

### \$wgP2GRepo

The name of the repository where the code must be uploaded

## Troubleshoot

To read detailed logging messages, you can intercept the [log group](https://www.mediawiki.org/wiki/Manual:$wgDebugLogGroups) named `PageToGitHub`: for instace with the following configuration into `LocalSetting.php`:

```
$wgShowExceptionDetails = true;
$wgDebugLogGroups['PageToGitHub'] = "/var/log/mediawiki/PageToGitHub-{$wgDBname}.log";
```

## Documentation

## License

[GNU General Public License, version 2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

## Maintainers

[Luca Mauri](https://github.com/lucamauri)

## Contributors

[Luca Mauri](https://github.com/lucamauri)
