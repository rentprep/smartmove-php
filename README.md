# SmartMove PHP Library

## Requirements

PHP 5.3.3 and later.

## Composer

You can install the library via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require rentprep/smartmove-php
```

To use the library, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/rentprep/smartmove-php/releases). Then, to use the library, include the `init.php` file.

```php
require_once('/path/to/smartmove-php/init.php');
```

## Getting Started

Simple usage looks like:

```php
use SmartMove\SmartMove;

SmartMove::setApiKey('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
SmartMove::setSandboxMode(true); //change to false for production

$referenceId = 'XXX'; // The unique ID in your system of the person who created the application
$applications = SmartMove::getApplications($referenceId);
```

Optionally, your application's user `$referenceId` can be set once, globally.

```php
use SmartMove\SmartMove;

SmartMove::setApiKey('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
SmartMove::setReferenceId('XXX');

$applications = SmartMove::getApplications();
```

## Usage

This is not intended to provide complete documentation of the API. For more
detail, please refer to the
[official documentation](https://stage.rentprep.com/docs/smartmoveapi).

###Applications

**Create new application**

```php
string SmartMove::createApplication([mixed $referenceId [, array $params]]);
```

`$referenceId` The unique ID in your system of the person who created the application

`$params` Additional options used to prefill fields of the application

Returns a URL where the applicaiton can be accessed


**List all applications**

```php
array SmartMove::getApplications([mixed $referenceId]);
```

`$referenceId` The unique ID in your system of the person who created the application

Returns an array of application objects

**Get application details**

```php
object SmartMove::getApplication(int $applicationId [, mixed $referenceId]);
```

`$applicationId` The ID of an application fetched from `SmartMove::getApplications`

`$referenceId` The unique ID in your system of the person who created the application

Returns application object

**Cancel application**

```php
bool SmartMove::cancelApplication(int $applicationId [, mixed $referenceId]);
```

`$applicationId` The ID of an application fetched from `SmartMove::getApplications`

`$referenceId` The unique ID in your system of the person who created the application

Returns true if the application was canceled, false otherwise

**Add applicate to application**

```php
bool SmartMove::addApplicant(int $applicationId, mixed $email [, mixed $referenceId]);
```

`$applicationId` The ID of an application fetched from `SmartMove::getApplications`

`$email` A string or array of applicant email addresses

`$referenceId` The unique ID in your system of the person who created the application

Returns true if the applicant was added to application, false otherwise

**Remove applicate from application**

```php
bool SmartMove::removeApplicant(int $applicationId, mixed $email [, mixed $referenceId]);
```

`$applicationId` The ID of an application fetched from `SmartMove::getApplications`

`$email` A string or array of applicant email addresses

`$referenceId` The unique ID in your system of the person who created the application

Returns true if the applicant was removed from the application, false otherwise

**Get report URL**

```php
string SmartMove::getReportUrl(int $applicationId [, mixed $referenceId]);
```

`$applicationId` The ID of an application fetched from `SmartMove::getApplications`

`$referenceId` The unique ID in your system of the person who created the application

Returns a URL where the applicaiton report can be accessed
