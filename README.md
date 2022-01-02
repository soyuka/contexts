# Updates done to original (archived) "behatch/contexts"

- Updated to minimum of PHP 8 as major version (`^8.0`)
- Removed allowing Symfony 2 & Symfony 3
- Changed Symfony 4 & 5 to LTS versions minimum versions
- Allowing Symfony 6
- Fixed all deprecations from previous versions (PHP & SF)

# Help wanted: 

- Deprecation warnings being thrown due to this overwriting original (which is still present and loaded) when using this 
bundle

# Using this bundle 

Add the following to your `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:rkeet/behatch-contexts.git"
    }
],
```

Change your branch dependency from 

```json
"behatch/contexts": "dev-php80 as 3.3.0",
```

to

```json
"behatch/contexts": "dev-php80",
```

---

Behatch contexts
================

[![Build status](https://travis-ci.org/Behatch/contexts.svg?branch=master)](https://travis-ci.org/Behatch/contexts)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Behatch/contexts/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Behatch/contexts/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Behatch/contexts/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Behatch/contexts/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ed08ea06-93c2-4b90-b65b-4364302b5108/mini.png)](https://insight.sensiolabs.com/projects/ed08ea06-93c2-4b90-b65b-4364302b5108)

Behatch contexts provide most common Behat tests.

Installation
------------

This extension requires:

* Behat 3+
* Mink
* Mink extension

### Project dependency

1. [Install Composer](https://getcomposer.org/download/)
2. Require the package with Composer:

```
$ composer require --dev behatch/contexts
```

3. Activate extension by specifying its class in your `behat.yml`:

```yaml
# behat.yml
default:
    # ...
    extensions:
        Behatch\Extension: ~
```

### Project bootstraping

1. Download the Behatch skeleton with composer:

```
$ php composer.phar create-project behatch/skeleton
```

Browser, json, table and rest step need a mink configuration, see [Mink
extension](https://github.com/Behat/MinkExtension) for more information.

Usage
-----

In `behat.yml`, enable desired contexts:

```yaml
default:
    suites:
        default:
            contexts:
                - behatch:context:browser
                - behatch:context:debug
                - behatch:context:system
                - behatch:context:json
                - behatch:context:table
                - behatch:context:rest
                - behatch:context:xml
```

### Examples

This project is self-tested, you can explore the [features
directory](./tests/features) to find some examples.

Configuration
-------------

* `browser` - more browser related steps (like mink)
    * `timeout` - default timeout
* `debug` - helper steps for debugging
    * `screenshotDir` - the directory where store screenshots
* `system` - shell related steps
    * `root` - the root directory of the filesystem
* `json` - JSON related steps
    * `evaluationMode` - javascript "foo.bar" or php "foo->bar"
* `table` - play with HTML the tables
* `rest` - send GET, POST, ... requests and test the HTTP headers
* `xml` - XML related steps

### Configuration Example

For example, if you want to change default directory to screenshots - you can do it this way:

```yaml
default:
    suites:
        default:
            contexts:
                - behatch:context:debug:
                    screenshotDir: "var"
```

Translation
-----------

[![See more information on Transifex.com](https://www.transifex.com/projects/p/behatch-contexts/resource/enxliff/chart/image_png)](https://www.transifex.com/projects/p/behatch-contexts/)
