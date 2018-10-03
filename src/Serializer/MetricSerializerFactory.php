<?php

namespace PNX\Prometheus\Serializer;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Creates a serializer for metrics.
 */
class MetricSerializerFactory {

  /**
   * Creates a new metric serializer.
   *
   * @return \Symfony\Component\Serializer\Serializer
   *   The serializer.
   */
  public static function create() {
    $encoders = [new PrometheusEncoder()];
    $objectNormalizer = new ObjectNormalizer(NULL, new CamelCaseToSnakeCaseNameConverter());
    $normalizers = [$objectNormalizer];
    return new Serializer($normalizers, $encoders);
  }

}
