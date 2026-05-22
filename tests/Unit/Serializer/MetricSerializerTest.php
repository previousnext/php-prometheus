<?php

namespace PNX\Prometheus\Tests\Unit\Serializer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Counter;
use PNX\Prometheus\Gauge;
use PNX\Prometheus\Serializer\MetricSerializerFactory;
use PNX\Prometheus\Summary;

/**
 * Tests for the MetricSerializerFactory class.
 */
#[CoversClass(MetricSerializerFactory::class)]
class MetricSerializerTest extends TestCase {

  /**
   * Tests serializing a gauge.
   */
  public function testSerializeGauge(): void {
    $serializer = MetricSerializerFactory::create();
    $gauge = $this->getTestGauge();
    $gaugeText = $serializer->serialize($gauge, 'prometheus');
    $expected = file_get_contents(__DIR__ . '/gauge.txt');
    $this->assertEquals($expected, $gaugeText);
  }

  /**
   * Tests serializing a counter.
   */
  public function testSerializeCounter(): void {
    $serializer = MetricSerializerFactory::create();
    $counter = $this->getTestCounter();
    $counterText = $serializer->serialize($counter, 'prometheus');
    $expected = file_get_contents(__DIR__ . '/counter.txt');
    $this->assertEquals($expected, $counterText);
  }

  /**
   * Tests serializing a summary.
   */
  public function testSerializeSummary(): void {
    $serializer = MetricSerializerFactory::create();
    $summary = $this->getTestSummary();
    $summaryText = $serializer->serialize($summary, 'prometheus');
    $expected = file_get_contents(__DIR__ . '/summary.txt');
    $this->assertEquals($expected, $summaryText);
  }

  /**
   * Gets a gauge for testing.
   *
   * @return \PNX\Prometheus\Gauge
   *   The gauge.
   */
  protected function getTestGauge(): Gauge {
    $gauge = new Gauge("foo", "bar", "A test gauge");
    $gauge->set(100, ['baz' => 'wiz']);
    $gauge->set(90, ['wobble' => 'wibble', 'bing' => 'bong']);
    $gauge->set(0);
    return $gauge;
  }

  /**
   * Gets a counter for testing.
   *
   * @return \PNX\Prometheus\Counter
   *   The counter.
   */
  protected function getTestCounter(): Counter {
    $counter = new Counter("foo", "bar", "A counter for testing");
    $counter->set(100, ['baz' => 'wiz']);
    return $counter;
  }

  /**
   * Gets a summary for testing.
   *
   * @return \PNX\Prometheus\Summary
   *   The summary.
   */
  protected function getTestSummary(): Summary {
    $summary = new Summary("foo", "bar", "Summary help text", 'baz');

    $buckets = [0, 0.25, 0.5, 0.75, 1];
    $values = [2, 4, 6, 8, 10];
    $summary->setValues($buckets, $values);
    $summary->setSum(54321);
    $summary->setCount(212);

    return $summary;
  }

}
