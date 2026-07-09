<?php

declare(strict_types=1);

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
  public function supportsEncoding(string $format): bool {
    return $format == self::ENCODING;
  }

  /**
   * {@inheritdoc}
   *
   * @param mixed $data
   *   The data to encode.
   * @param string $format
   *   The encoding format.
   * @param array<string, mixed> $context
   *   The serializer context.
   */
  public function encode(mixed $data, string $format, array $context = []): string {
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
   * @param array<string, string|int|float> $labels
   *   The labels.
   *
   * @return string
   *   The labels in prometheus format.
   */
  protected function encodeLabels(array $labels): string {
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
   * @param string|int|float $value
   *   The raw value.
   *
   * @return string
   *   The escaped value.
   */
  protected function escapeValue(string|int|float $value): string {
    $value = (string) $value;
    $value = str_replace("\\", "\\\\", $value);
    $value = str_replace("\"", "\\\"", $value);
    $value = str_replace("\n", "\\n", $value);
    return $value;
  }

}
