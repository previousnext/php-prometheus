<?php

namespace PNX\Prometheus;

/**
 * Value object representing a Prometheus gauge type.
 */
class Gauge extends Metric {

  /**
   * The metric type.
   */
  const TYPE_GAUGE = "gauge";

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return self::TYPE_GAUGE;
  }

  /**
   * Adds a value for this metric.
   *
   * @param mixed $value
   *   The value.
   * @param array<string, string|int|float> $labels
   *   The list of key value label pairs.
   */
  public function set(mixed $value, array $labels = []): void {
    $key = $this->getKey($labels);
    $this->labelledValues[$key] = new LabelledValue($this->getName(), $value, $labels);
  }

}
