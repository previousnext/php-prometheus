<?php

namespace PNX\Prometheus\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PNX\Prometheus\Summary;

/**
 * @coversDefaultClass \PNX\Prometheus\Summary
 */
class SummaryTest extends TestCase {

  /**
   * @covers ::setValues
   */
  public function testSummary() {
    $summary = new Summary("foo", "bar", "Summary help text", 'baz');

    $buckets = [0, 0.25, 0.5, 0.75, 1];
    $values = [2, 4, 6, 8, 10];
    $summary->setValues($buckets, $values);

    $this->assertEquals("foo_bar", $summary->getName());
    $this->assertEquals("summary", $summary->getType());

    $values = $summary->getLabelledValues();
    $this->assertCount(7, $values);

    $value1 = $values[0];
    $this->assertEquals(2, $value1->getValue());
    $this->assertEquals(['baz' => 0], $value1->getLabels());

    $sum = $values[5];
    $this->assertEquals(30, $sum->getValue());
    $this->assertEmpty($sum->getLabels());

    $count = $values[6];
    $this->assertEquals(5, $count->getValue());
    $this->assertEmpty($count->getLabels());
  }

}
