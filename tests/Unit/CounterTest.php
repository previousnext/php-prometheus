<?php

declare(strict_types=1);

namespace PNX\Prometheus\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Counter;

/**
 * Tests for the Counter class.
 */
#[CoversClass(Counter::class)]
class CounterTest extends TestCase {

  /**
   * Tests setting counter values.
   */
  public function testCounter(): void {
    $counter = new Counter('foo', 'bar', 'Example counter help');
    $counter->set(89, ['baz' => 'wiz']);

    $this->assertEquals("foo_bar", $counter->getName());
    $this->assertEquals("counter", $counter->getType());

    $values = $counter->getLabelledValues();
    $this->assertCount(1, $values);
    $this->assertEquals(89, $values[0]->getValue());
  }

  /**
   * Tests that a negative value throws an exception.
   */
  public function testNegativeValueThrows(): void {
    $counter = new Counter('foo', 'bar', 'Example counter help');
    $this->expectException(\InvalidArgumentException::class);
    // @phpstan-ignore argument.type
    $counter->set(-1, ['baz' => 'wiz']);
  }

  /**
   * Tests that a non-int value triggers a deprecation notice.
   */
  public function testNonIntValueIsDeprecated(): void {
    $counter = new Counter('foo', 'bar', 'Example counter help');
    $this->expectUserDeprecationMessage('Passing a non-int value to PNX\Prometheus\Counter::set() is deprecated in php_prometheus:1.1.0 and will throw a \TypeError in php_prometheus:2.0.0. See https://github.com/previousnext/php-prometheus/issues/16');

    try {
      // @phpstan-ignore argument.type
      $counter->set('bing', ['baz' => 'wiz']);
    }
    catch (\InvalidArgumentException) {
      // The deprecation is triggered before the exception is thrown.
    }
  }

  /**
   * Tests that a non-int value still throws an exception.
   */
  #[IgnoreDeprecations]
  public function testNonIntValueThrows(): void {
    $counter = new Counter('foo', 'bar', 'Example counter help');
    $this->expectException(\InvalidArgumentException::class);
    // @phpstan-ignore argument.type
    @$counter->set('bing', ['baz' => 'wiz']);
  }

  /**
   * Tests that invalid counter names throw an exception.
   */
  public function testInvalidCounter(): void {
    $this->expectException(\InvalidArgumentException::class);
    new Counter('foo^&**', 'bar&*', 'Example counter help');
  }

}
