<?php

namespace PNX\Prometheus\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Counter;

/**
 * @coversDefaultClass \PNX\Prometheus\Counter
 */
class CounterTest extends TestCase {

  /**
   * @covers ::set
   */
  public function testCounter() {
    $counter = new Counter('foo', 'bar', 'Example counter help');
    $counter->set(89, ['baz' => 'wiz']);

    $this->assertEquals("foo_bar", $counter->getFullName());
    $this->assertEquals("counter", $counter->getType());

    $this->expectException(\InvalidArgumentException::class);
    $counter->set(-1, ['baz' => 'wiz']);

    $this->expectException(\InvalidArgumentException::class);
    $counter->set('bing', ['baz' => 'wiz']);
  }

}
