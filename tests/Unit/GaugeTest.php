<?php

declare(strict_types=1);

namespace PNX\Prometheus\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Gauge;

/**
 * Tests for the Gauge class.
 */
#[CoversClass(Gauge::class)]
class GaugeTest extends TestCase {

  /**
   * Tests setting gauge values.
   */
  public function testGauge(): void {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    $gauge->set(100, ['baz' => 'wiz']);
    $gauge->set(90, ['wobble' => 'wibble', 'bing' => 'bong']);
    $gauge->set(0);

    $this->assertEquals("foo_bar", $gauge->getName());
    $this->assertEquals("gauge", $gauge->getType());

    $values = $gauge->getLabelledValues();
    $this->assertCount(3, $values);

    $value1 = $values[0];
    $this->assertEquals(100, $value1->getValue());

    $labels = $value1->getLabels();
    $this->assertCount(1, $labels);

  }

  /**
   * Ensure a gauge with no values is valid.
   */
  public function testGaugeNoValues(): void {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    $this->assertEmpty($gauge->getLabelledValues());
  }

  /**
   * Tests that int and float values are accepted without a deprecation notice.
   */
  #[DataProvider('numericValueProvider')]
  public function testGaugeAcceptsNumericValue(int|float $value): void {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    $gauge->set($value);

    $values = $gauge->getLabelledValues();
    $this->assertCount(1, $values);
    $this->assertEquals($value, $values[0]->getValue());
  }

  /**
   * Provides int and float values accepted by Gauge::set().
   *
   * @return array<string, array{int|float}>
   *   The numeric values to test.
   */
  public static function numericValueProvider(): array {
    return [
      'integer' => [100],
      'zero' => [0],
      'negative integer' => [-5],
      'float' => [1.5],
      'negative float' => [-2.75],
    ];
  }

  /**
   * Tests that a non-int/float value triggers a deprecation notice.
   */
  public function testGaugeNonNumericValueIsDeprecated(): void {
    $this->expectUserDeprecationMessage('Passing a non-int/float value to PNX\Prometheus\Gauge::set() is deprecated in php_prometheus:1.1.0 and will throw a \TypeError in php_prometheus:2.0.0. See https://github.com/previousnext/php-prometheus/issues/14');

    $gauge = new Gauge("foo", "bar", "A test gauge");
    $gauge->set("not-a-number");
  }

  /**
   * Tests that a deprecated value is still stored.
   */
  #[IgnoreDeprecations]
  public function testGaugeNonNumericValueIsStored(): void {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    @$gauge->set("not-a-number");

    $values = $gauge->getLabelledValues();
    $this->assertCount(1, $values);
    $this->assertEquals("not-a-number", $values[0]->getValue());
  }

}
