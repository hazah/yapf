<?php
/**
 * @file
 *
 * This plugin enables a hook system. 
 */

function add_action($name, $callback, $weight = 0) {
  global $actions;
  
  if (is_callable($callback)) {
    $actions[$name][$weight][] = $callback;
  }
}


function do_action() {
  global $actions;
  
  $args = func_get_args();
  $name = array_shift($args);
  
  if (isset($actions[$name])) {
    ksort($actions[$name]);
    foreach ($actions[$name] as $handlers) {
      foreach ($handlers as $handler) {
        call_user_func_array($handler, $args);
      }
    }
  }
}

return false;
