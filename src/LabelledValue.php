<?php

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
   *
   * @var mixed
   */
  protected $value;

  /**
   * The key value pairs of labels.
   *
   * @var array
   */
  protected $labels;

  /**
   * Value constructor.
   *
   * @param mixed $value
   *   The metric value.
   * @param array[] $labels
   *   The key value pairs of labels.
   */
  public function __construct($value, array $labels = []) {
    $this->value = $value;
    foreach ($labels as $name => $v) {
      if (!preg_match(self::LABEL_NAME_REGEX, $name)) {
        throw new \InvalidArgumentException("Invalid label name: '" . $name . "'");
      }
    }
    $this->labels = $labels;
  }

  /**
   * Gets the Value.
   *
   * @return mixed
   *   The Value.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Gets the Labels.
   *
   * @return array[]
   *   The Labels.
   */
  public function getLabels(): array {
    return $this->labels;
  }

}
