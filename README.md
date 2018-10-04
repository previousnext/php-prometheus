# PHP Prometheus Serializer

A PHP library for serializing to the prometheus text format.

[![CircleCI](https://circleci.com/gh/previousnext/php-prometheus.svg?style=svg)](https://circleci.com/gh/previousnext/php-prometheus)

**NOTE** This library does not keep state. It is intended purely as a serialization library. Therefore, there are no
methods into increment or decrement values for metrics, only to set them in order to be serialized.

## Installation

```
composer require previousnext/php-prometheus
```

## Usage

### Gauge

```php
$gauge = new Gauge("foo", "bar", "A test gauge");
$gauge->set(100, ['baz' => 'wiz']);
$gauge->set(90, ['wobble' => 'wibble', 'bing' => 'bong']);
$gauge->set(0);

$serializer = MetricSerializerFactory::create();
$output = $serializer->serialize($gauge, 'prometheus');
```

Expected output:

```text
# HELP foo_bar A test gauge
# TYPE foo_bar gauge
foo_bar{baz="wiz"} 100
foo_bar{wobble="wibble",bing="bong"} 90
foo_bar 0
```

## Developing

PHP CodeSniffer
```
./bin/phpcs
```

PHPUnit

```
./bin/phpunit
```
