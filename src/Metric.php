<?php

declare(strict_types=1);

namespace PNX\Prometheus;

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
   */
  protected string $name;

  /**
   * The help message for the metric.
   */
  protected string|\Stringable $help;

  /**
   * The metric values.
   *
   * @var \PNX\Prometheus\LabelledValue[]
   */
  protected array $labelledValues = [];

  /**
   * Metric constructor.
   *
   * @param string $namespace
   *   The metric namespace.
   * @param string $name
   *   The metric name.
   * @param string|\Stringable $help
   *   The help message for the metric.
   */
  public function __construct(string $namespace, string $name, string|\Stringable $help) {
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
    return (string) $this->help;
  }

  /**
   * Gets the values for this metric.
   *
   * @return \PNX\Prometheus\LabelledValue[]
   *   The array of values.
   */
  public function getLabelledValues(): array {
    return array_values($this->labelledValues);
  }

  /**
   * Validates a metric or label name.
   *
   * @param string $name
   *   The metric or label name.
   *
   * @throws \InvalidArgumentException
   *   If the name is invalid.
   */
  protected function validateName(string $name): void {
    if (!preg_match(self::METRIC_NAME_REGEX, $name)) {
      throw new \InvalidArgumentException("Invalid name: '" . $name . "'");
    }
  }

  /**
   * Generates a unique key for the specified labels.
   *
   * @param array<string, string|int|float> $labels
   *   The labels.
   *
   * @return string
   *   A unique key for the labels.
   */
  protected function getKey(array $labels): string {
    return md5(json_encode($labels, JSON_FORCE_OBJECT));
  }

}
