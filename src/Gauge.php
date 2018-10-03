<?php

namespace PNX\Prometheus;

/**
 * Value object representing a Prometheus gauge type.
 */
class Gauge extends Metric {

  /**
   * The metric type.
   */
  const TYPE = "gauge";

  /**
   * The metric values.
   *
   * @var \PNX\Prometheus\LabelledValue[]
   */
  protected $values;

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return self::TYPE;
  }

  /**
   * Adds a value for this metric.
   *
   * @param mixed $value
   *   The value.
   * @param array $labels
   *   The list of key value label pairs.
   */
  public function set($value, array $labels = []) {
    $key = $this->getKey($labels);
    $this->values[$key] = new LabelledValue($value, $labels);
  }

  /**
   * Gets the values for this metric.
   *
   * @return \PNX\Prometheus\LabelledValue[]
   *   The array of values.
   */
  public function getValues() {
    return array_values($this->values);
  }

}
