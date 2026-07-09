<?php

declare(strict_types=1);

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
   *   The value. Non-int/float values are deprecated.
   * @param array<string, string|int|float> $labels
   *   The list of key value label pairs.
   */
  public function set(mixed $value, array $labels = []): void {
    if (!is_int($value) && !is_float($value)) {
      // phpcs:ignore Drupal.Semantics.FunctionTriggerError.TriggerErrorSeeUrlFormat
      @trigger_error(sprintf('Passing a non-int/float value to %s() is deprecated in php_prometheus:1.1.0 and will throw a \TypeError in php_prometheus:2.0.0. See https://github.com/previousnext/php-prometheus/issues/14', __METHOD__), E_USER_DEPRECATED);
    }
    $key = $this->getKey($labels);
    $this->labelledValues[$key] = new LabelledValue($this->getName(), $value, $labels);
  }

}
