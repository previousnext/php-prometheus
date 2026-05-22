<?php

declare(strict_types=1);

namespace PNX\Prometheus;

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
   * @param array<string, string|int|float> $labels
   *   The list of key value label pairs.
   */
  public function set(mixed $value, array $labels = []): void {
    if (!$this->isValidValue($value)) {
      throw new \InvalidArgumentException("A count value must be a positive integer.");
    }
    $key = $this->getKey($labels);
    $this->labelledValues[$key] = new LabelledValue($this->getName(), $value, $labels);
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
  protected function isValidValue(mixed $value): bool {
    return is_int($value) && $value >= 0;
  }

}
