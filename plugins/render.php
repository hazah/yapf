<?php

function render($content, $plugin = null) {
  global $plugins;
  
  $output = '';
  if (is_array($content)) {
    foreach (array_keys($content) as $key) {
      $renderable = $content[$key];
    
      if (is_string($key)) {
        foreach ($plugins as $info) {
          if ($key == $info['name']) {
            $output .= render($renderable, $key);
            continue 2;
          }
        }
        
        if ($plugin) {
          $function = "render_alter_{$plugin}_{$key}";
          if (function_exists($function)) {
            $renderable = $function($renderable);
          }
        }
      }
      
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

return false;
