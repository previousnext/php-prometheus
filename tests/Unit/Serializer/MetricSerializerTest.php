<?php

namespace PNX\Prometheus\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Gauge;
use PNX\Prometheus\Serializer\MetricSerializerFactory;

/**
 * @coversDefaultClass \PNX\Prometheus\Serializer\MetricSerializerFactory
 */
class MetricSerializerTest extends TestCase {

  /**
   * @covers ::create
   */
  public function testSerialize() {
    $serializer = MetricSerializerFactory::create();
    $gauge = $this->getTestGauge();
    $prom = $serializer->serialize($gauge, 'prometheus');
    $expected = file_get_contents(__DIR__ . '/gauge.txt');
    $this->assertEquals($expected, $prom);
  }

  /**
   * Gets a gauge for testing.
   *
   * @return \PNX\Prometheus\Gauge
   *   The gauge.
   */
  protected function getTestGauge() {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    $gauge->set(100, ['baz' => 'wiz']);
    $gauge->set(90, ['wobble' => 'wibble', 'bing' => 'bong']);
    $gauge->set(0);
    return $gauge;
  }

}
