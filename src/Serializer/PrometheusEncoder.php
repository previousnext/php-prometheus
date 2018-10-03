<?php

namespace PNX\Prometheus\Serializer;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Provides and encoder for Prometheus text format.
 */
class PrometheusEncoder implements EncoderInterface {

  const ENCODING = 'prometheus';

  /**
   * {@inheritdoc}
   */
  public function supportsEncoding($format) {
    return $format == self::ENCODING;
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = []) {
    $output = [];
    $output[] = '# HELP ' . $data['full_name'] . ' ' . $data['help'];
    $output[] = '# TYPE ' . $data['full_name'] . ' ' . $data['type'];

    foreach ($data['values'] as $value) {
      $output[] = $data['full_name'] . $this->encodeLabels($value['labels']) . ' ' . $value['value'];
    }
    return implode("\n", $output) . "\n";
  }

  /**
   * Encode the labels as in the prometheus format.
   *
   * @param array $labels
   *   The labels.
   *
   * @return string
   *   The labels in prometheus format.
   */
  protected function encodeLabels(array $labels) {
    if (empty($labels)) {
      return '';
    }
    $output = [];
    foreach ($labels as $key => $value) {
      $output[] = $key . '="' . $value . '"';
    }
    return '{' . implode(',', $output) . '}';
  }

}
