<?php

declare(strict_types=1);

namespace PNX\Prometheus;

/**
 * Value object representing a Prometheus summary type.
 */
class Summary extends Metric {

  /**
   * The metric type.
   */
  const TYPE_SUMMARY = "summary";

  /**
   * The sum of all values.
   */
  protected float $sum;

  /**
   * The count of all values.
   */
  protected int $count;

  /**
   * The metric label.
   */
  protected string $label;

  /**
   * Summary constructor.
   *
   * @param string $namespace
   *   The metric namespace.
   * @param string $name
   *   The metric name.
   * @param string|\Stringable $help
   *   The help message for the metric.
   * @param string $label
   *   The label name for the summary buckets.
   */
  public function __construct(string $namespace, string $name, string|\Stringable $help, string $label) {
    parent::__construct($namespace, $name, $help);
    $this->sum = 0;
    $this->count = 0;
    $this->validateName($label);
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return self::TYPE_SUMMARY;
  }

  /**
   * Sets an array of summary values.
   *
   * @param array<int|float> $buckets
   *   The list of buckets.
   * @param array<int|float> $values
   *   The list of bucket values.
   *
   * @throws \InvalidArgumentException
   *   If the number of buckets and values do not match.
   */
  public function setValues(array $buckets, array $values): void {
    if (count($buckets) != count($values)) {
      throw new \InvalidArgumentException("The number of buckets and values must match.");
    }
    foreach ($buckets as $index => $bucket) {
      $labels = [$this->label => $bucket];
      $key = $this->getKey($labels);
      $value = $values[$index];
      $this->labelledValues[$key] = new LabelledValue($this->getName(), $value, $labels);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getLabelledValues(): array {
    $labelledValues = parent::getLabelledValues();
    $labelledValues[] = new LabelledValue($this->getName() . '_sum',
      $this->sum, []);
    $labelledValues[] = new LabelledValue($this->getName() . '_count',
      $this->count, []);
    return $labelledValues;
  }

  /**
   * Sets the Sum.
   *
   * @param float $sum
   *   The Sum.
   */
  public function setSum(float $sum): void {
    $this->sum = $sum;
  }

  /**
   * Sets the Count.
   *
   * @param int $count
   *   The Count.
   */
  public function setCount(int $count): void {
    $this->count = $count;
  }

  /**
   * Gets the Sum.
   *
   * @return float
   *   The Sum.
   */
  public function getSum(): float {
    return $this->sum;
  }

  /**
   * Gets the Count.
   *
   * @return int
   *   The Count.
   */
  public function getCount(): int {
    return $this->count;
  }

  /**
   * Gets the Label.
   *
   * @return string
   *   The Label.
   */
  public function getLabel(): string {
    return $this->label;
  }

}
