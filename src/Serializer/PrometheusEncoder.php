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
  public function supportsEncoding($format): bool {
    return $format == self::ENCODING;
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = []): string {
    $output = [];
    $output[] = '# HELP ' . $data['name'] . ' ' . $data['help'];
    $output[] = '# TYPE ' . $data['name'] . ' ' . $data['type'];

    foreach ($data['labelled_values'] as $labelledValue) {
      $output[] = $labelledValue['name'] . $this->encodeLabels($labelledValue['labels']) . ' ' . $this->escapeValue($labelledValue['value']);
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

  /**
   * Escape special characters in values.
   *
   * @param string $value
   *   The raw value.
   *
   * @return string
   *   The escaped value.
   */
  protected function escapeValue($value) {
    $value = str_replace("\"", "\\\"", $value);
    $value = str_replace("\n", "\\n", $value);
    $value = str_replace("\\", "\\\\", $value);
    return $value;
  }

}
