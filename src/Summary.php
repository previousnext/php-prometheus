<?php

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
   * {@inheritdoc}
   */
  public function getType(): string {
    return self::TYPE_SUMMARY;
  }

}
