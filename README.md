Behatch contexts
================

[![CI](https://github.com/soyuka/contexts/actions/workflows/ci.yml/badge.svg)](https://github.com/soyuka/contexts/actions/workflows/ci.yml)

Behatch contexts provide most common Behat tests.

Installation
------------

This extension requires:

* PHP 8.1+
* Behat 3.20+ or 4
* Mink
* Mink extension

### Project dependency

1. [Install Composer](https://getcomposer.org/download/)
2. Require the package with Composer:

```
$ composer require --dev soyuka/contexts
```

3. Activate extension by specifying its class in your `behat.php`:

```php
// behat.php
use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behatch\Extension as BehatchExtension;

return (new Config())
    ->withProfile((new Profile('default'))
        // ...
        ->withExtension(new Extension(BehatchExtension::class)));
```

Or in your `behat.yml` (Behat 3 only, Behat 4 reads PHP configuration only):

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
extension](https://github.com/FriendsOfBehat/MinkExtension) for more information.

Usage
-----

In `behat.php`, enable desired contexts:

```php
// behat.php
use Behat\Config\Config;
use Behat\Config\Profile;
use Behat\Config\Suite;

return (new Config())
    ->withProfile((new Profile('default'))
        ->withSuite((new Suite('default'))
            ->addContext('behatch:context:browser')
            ->addContext('behatch:context:debug')
            ->addContext('behatch:context:system')
            ->addContext('behatch:context:json')
            ->addContext('behatch:context:table')
            ->addContext('behatch:context:rest')
            ->addContext('behatch:context:xml')));
```

Or in `behat.yml`:

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

```php
(new Suite('default'))
    ->addContext('behatch:context:debug', [
        'screenshotDir' => 'var',
    ])
```

```yaml
default:
    suites:
        default:
            contexts:
                - behatch:context:debug:
                    screenshotDir: "var"
```
