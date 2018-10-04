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

### Counter

```php
$counter = new Counter("foo", "bar", "A counter for testing");
$counter->set(100, ['baz' => 'wiz']);

$serializer = MetricSerializerFactory::create();
$output = $serializer->serialize($counter, 'prometheus');
```

Expected output:

```text
# HELP foo_bar A counter for testing
# TYPE foo_bar counter
foo_bar{baz="wiz"} 100
```

### Summary

```php
$summary = new Summary("foo", "bar", "Summary help text", 'test_bucket');
$buckets = [0, 0.25, 0.5, 0.75, 1];
$values = [2, 4, 6, 8, 10];
$summary->setValues($buckets, $values);

$serializer = MetricSerializerFactory::create();
$output = $serializer->serialize($summary, 'prometheus');
```

Expected output:

```text
# HELP foo_bar Summary help text
# TYPE foo_bar summary
foo_bar{test_bucket="0"} 2
foo_bar{test_bucket="0.25"} 4
foo_bar{test_bucket="0.5"} 6
foo_bar{test_bucket="0.75"} 8
foo_bar{test_bucket="1"} 10
foo_bar_sum 30
foo_bar_count 5
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
