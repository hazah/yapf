<?php

function theme($type, $settings) {
  global $theme;

  if (empty($theme))
    $theme = array();

  $settings += array(
    'format' => null,
  );

  extract($settings);

  $theme[$plugin][$type][] = theme_alter($plugin, $type, $format, $content);
}

function theme_alter($plugin, $type, $format, $content) {
  $function = "theme_alter_{$plugin}_{$type}";
  if (function_exists($function)) {
    $content = $function($format, $content);
  }
  $function = "theme_alter_{$plugin}";
  if (function_exists($function)) {
    $content = $function($type, $format, $content);
  }
  return $content;
}

return false;
