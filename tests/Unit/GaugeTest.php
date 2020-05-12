<?php

namespace PNX\Prometheus\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Gauge;

/**
 * @coversDefaultClass \PNX\Prometheus\Gauge
 */
class GaugeTest extends TestCase {

  /**
   * @covers ::__construct
   * @covers ::set
   * @covers ::getLabelledValues
   */
  public function testGauge() {
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
   *
   * @covers ::__construct
   * @covers ::getLabelledValues
   */
  public function testGaugeNoValues() {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    $this->assertEmpty($gauge->getLabelledValues());
  }

}
