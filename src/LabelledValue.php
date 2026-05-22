<?php

declare(strict_types=1);

namespace PNX\Prometheus;

/**
 * Value object representing a metric value.
 */
class LabelledValue {

  /**
   * The regex for valid label names.
   */
  const LABEL_NAME_REGEX = '/^[a-zA-Z_:][a-zA-Z0-9_:]*$/';

  /**
   * The metric value.
   */
  protected mixed $value;

  /**
   * The key value pairs of labels.
   *
   * @var array<string, string|int|float>
   */
  protected array $labels;

  /**
   * The name override for this labelled value.
   */
  protected string $name;

  /**
   * Value constructor.
   *
   * @param string $name
   *   The name override for this label.
   * @param mixed $value
   *   The metric value.
   * @param array<string, string|int|float> $labels
   *   The key value pairs of labels.
   *
   * @throws \InvalidArgumentException
   *   If the name or label names are invalid.
   */
  public function __construct(string $name, mixed $value, array $labels = []) {
    $this->value = $value;
    foreach ($labels as $labelKey => $labelValue) {
      if (!preg_match(self::LABEL_NAME_REGEX, $labelKey)) {
        throw new \InvalidArgumentException("Invalid label name: '" . $labelKey . "'");
      }
    }
    if (!empty($name) && !preg_match(self::LABEL_NAME_REGEX, $name)) {
      throw new \InvalidArgumentException("Invalid metric name: '" . $name . "'");
    }
    $this->name = $name;
    $this->labels = $labels;
  }

  /**
   * Gets the Value.
   *
   * @return mixed
   *   The Value.
   */
  public function getValue(): mixed {
    return $this->value;
  }

  /**
   * Gets the Labels.
   *
   * @return array<string, string|int|float>
   *   The Labels.
   */
  public function getLabels(): array {
    return $this->labels;
  }

  /**
   * Gets the Name override.
   *
   * @return string
   *   The Name.
   */
  public function getName(): string {
    return $this->name;
  }

}
