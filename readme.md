# Mandrill Mailer
Easy-to-use PHP library for sending emails with Mandrill.

[![Latest Stable Version](https://poser.pugx.org/ataccama/mandrill/v/stable)](https://packagist.org/packages/ataccama/mandrill) [![Total Downloads](https://poser.pugx.org/ataccama/mandrill/downloads)](https://packagist.org/packages/ataccama/mandrill) [![Latest Unstable Version](https://poser.pugx.org/ataccama/mandrill/v/unstable)](https://packagist.org/packages/ataccama/mandrill) [![License](https://poser.pugx.org/ataccama/mandrill/license)](https://packagist.org/packages/ataccama/mandrill) [![Monthly Downloads](https://poser.pugx.org/ataccama/mandrill/d/monthly)](https://packagist.org/packages/ataccama/mandrill) [![Daily Downloads](https://poser.pugx.org/ataccama/mandrill/d/daily)](https://packagist.org/packages/ataccama/mandrill) [![composer.lock](https://poser.pugx.org/ataccama/mandrill/composerlock)](https://packagist.org/packages/ataccama/mandrill)

## Installation
```
composer require ataccama/mandrill
```

## Usage

```php
$mandrill = new Ataccama\MandrillMailer(API_KEY, SUB_ACCOUNT);
```

### Send Mandrill template
```php
$mandrill
    ->addFrom('email@address.com', 'Name')
    ->addTo('email@address.com')
    ->setSubject('Email subject')
    ->templateName('tmp-name');
```

#### Add variables to template
```php
$mandrill->addAttributes([
   'variable_key_1' => 'variable 1 content',
   'variable_key_2' => 'variable 2 content'
]);
```

### Add attachments
```php
addAttachment("filename.txt", "file content")
```

### Send basic html email
```php
$mandrill
    ->addFrom('email@address.com')
    ->addTo('email@address.com', 'Name')
    ->setSubject('Events confirmation')
    ->setHtmlBody('<h1>Love you</h1><p>Soo <strong>much</strong>!</p>');
```

### Send
```php
$mandrill->send();
```