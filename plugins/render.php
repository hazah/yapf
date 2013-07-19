<?php
/**
 * @file
 *
 * This API plugin helps with the transformation of renderable arrays into HTML.
 */
function render($content, $plugin = null) {
  global $plugins;
  $output = '';

  if (is_array($content)) {
    foreach (array_keys($content) as $key) {
      $renderable = $content[$key];

      if (is_string($key)) {
        // If the key is the name of a plugin, record it so that we can specify
        // how to properly alter the array.
        foreach ($plugins as $plugins_info) {
          foreach ($plugins_info as $info) {
            if ($key == $info['name']) {
              $output .= render($renderable, $key);

              // The render call above handled the plugin key, so we continue
              // to render the key that comes just after it.
              continue 3;
            }
          }
        }

        // We're rendering within a specific plugin
        if ($plugin) {
          $function = "render_alter_{$plugin}_{$key}";
          if (function_exists($function)) {
            $renderable = $function($renderable);
          }
        }
      }

      // We're rendering within a specific plugin
      if ($plugin) {
        $function = "render_alter_{$plugin}";
        if (function_exists($function)) {
          $renderable = $function($renderable, $key);
        }
      }

      $output .= render($renderable, $plugin);
    }
  }
  else {
    $output .= $content;
  }
  return $output;
}
