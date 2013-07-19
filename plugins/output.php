<?php
/**
 * @file
 *
 * This plugin helps with creation of renderable arrays for output.
 */
function output($type, $settings) {
  global $output;

  if (empty($output))
    $output = array();

  $settings += array(
    'format' => null,
  );

  extract($settings);

  $output[$plugin][$type][] = output_alter($plugin, $type, $format, $content);
}

function output_alter($plugin, $type, $format, $content) {
  $function = "output_alter_{$plugin}_{$type}";
  if (function_exists($function)) {
    $content = $function($format, $content);
  }
  $function = "output_alter_{$plugin}";
  if (function_exists($function)) {
    $content = $function($type, $format, $content);
  }
  return $content;
}
