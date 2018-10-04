<?php

namespace PNX\Prometheus;

use InvalidArgumentException;

/**
 * Value object to represent a prometheus metric.
 */
abstract class Metric {

  /**
   * The regex for valid label names.
   */
  const METRIC_NAME_REGEX = '/^[a-zA-Z_:][a-zA-Z0-9_:]*$/';

  /**
   * The metric name.
   *
   * @var string
   */
  protected $name;

  /**
   * The help message for the metric.
   *
   * @var string
   */
  protected $help;

  /**
   * The metric values.
   *
   * @var \PNX\Prometheus\LabelledValue[]
   */
  protected $labelledValues;

  /**
   * Metric constructor.
   *
   * @param string $namespace
   *   The metric namespace.
   * @param string $name
   *   The metric name.
   * @param string $help
   *   The help message for the metric.
   */
  public function __construct(string $namespace, string $name, string $help) {
    $fullName = $namespace . '_' . $name;
    $this->validateName($fullName);
    $this->name = $fullName;
    $this->help = $help;
  }

  /**
   * Gets the metric type.
   *
   * @return string
   *   The metric type.
   */
  abstract public function getType(): string;

  /**
   * Gets the metric name.
   *
   * @return string
   *   The metric name.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Gets the Help.
   *
   * @return string
   *   The Help.
   */
  public function getHelp(): string {
    return $this->help;
  }

  /**
   * Gets the values for this metric.
   *
   * @return \PNX\Prometheus\LabelledValue[]
   *   The array of values.
   */
  public function getLabelledValues() {
    return array_values($this->labelledValues);
  }

  /**
   * Validates a metric or label name.
   *
   * @param string $name
   *   The metric or label name.
   */
  protected function validateName($name): void {
    if (!preg_match(self::METRIC_NAME_REGEX, $name)) {
      throw new InvalidArgumentException("Invalid name: '" . $name . "'");
    }
  }

  /**
   * Generates a unique key for the specified labels.
   *
   * @param array $labels
   *   The labels.
   *
   * @return string
   *   A unique key for the labels.
   */
  protected function getKey(array $labels) {
    return md5(json_encode($labels, JSON_FORCE_OBJECT));
  }

}
