<?php

function theme($type, $settings) {
  global $theme;

  if (empty($theme))
    $theme = array();

  $settings += array(
    'format' => null,
  );

  extract($settings);

  if (!isset($theme[$plugin][$type])) {
    $theme[$plugin][$type] = '';
  }

  $theme[$plugin][$type] .= theme_override($plugin, $type, $format, $content);
}

function theme_override($plugin, $type, $format, $content) {
  $function = "theme_{$plugin}_{$type}";
  if (function_exists($function)) {
    $content = $function($format, $content);
  }
  $function = "theme_{$plugin}";
  if (function_exists($function)) {
    $content = $function($type, $format, $content);
  }
  return $content;
}

return false;
