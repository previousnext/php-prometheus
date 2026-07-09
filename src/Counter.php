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
   * @param int<0, max> $value
   *   The value. Non-int values are deprecated.
   * @param array<string, string|int|float> $labels
   *   The list of key value label pairs.
   *
   * @throws \InvalidArgumentException
   *   If the value is not a non-negative integer.
   */
  public function set(mixed $value, array $labels = []): void {
    // @phpstan-ignore function.alreadyNarrowedType
    if (!is_int($value)) {
      // phpcs:ignore Drupal.Semantics.FunctionTriggerError.TriggerErrorSeeUrlFormat
      @trigger_error(sprintf('Passing a non-int value to %s() is deprecated in php_prometheus:1.1.0 and will throw a \TypeError in php_prometheus:2.0.0. See https://github.com/previousnext/php-prometheus/issues/16', __METHOD__), E_USER_DEPRECATED);
    }
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
