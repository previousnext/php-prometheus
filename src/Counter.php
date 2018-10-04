<?php

namespace PNX\Prometheus;

use InvalidArgumentException;

/**
 * Value object representing a Prometheus counter type.
 */
class Counter extends Metric {

  /**
   * The metric type.
   */
  const TYPE_COUNTER = "counter";

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return self::TYPE_COUNTER;
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
    if (!$this->isValidValue($value)) {
      throw new InvalidArgumentException("A count value must be a positive integer.");
    }
    $key = $this->getKey($labels);
    $this->labelledValues[$key] = new LabelledValue($value, $labels);
  }

  /**
   * Check if the value is valid.
   *
   * @param mixed $value
   *   The value.
   *
   * @return bool
   *   TRUE if the value is valid. FALSE otherwise.
   */
  protected function isValidValue($value) {
    return is_int($value) && $value >= 0;
  }

}
