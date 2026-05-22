<?php

namespace PNX\Prometheus\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
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

    $this->expectException(\InvalidArgumentException::class);
    $counter->set(-1, ['baz' => 'wiz']);

    $this->expectException(\InvalidArgumentException::class);
    $counter->set('bing', ['baz' => 'wiz']);
  }

  /**
   * Tests that invalid counter names throw an exception.
   */
  public function testInvalidCounter(): void {
    $this->expectException(\InvalidArgumentException::class);
    new Counter('foo^&**', 'bar&*', 'Example counter help');
  }

}
