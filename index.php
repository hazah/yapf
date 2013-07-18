<?php

/** YAPF Begins here */
if ($handle = opendir('./plugins')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      // Each plugin file could return plugin information. If so, record it.
      $info = require_once './plugins/' . $entry;
      if ($info === false) {
        $info = array();
      }
        // Set defaults
      $info += array(
        'weight' => 0,
        'name' => str_replace('.php', '', $entry),
      );
      $plugins[$info['weight']][] = $info;
    }
  }
  closedir($handle);
  unset($handle);
  unset($entry);
  
  // We use a weight to sort the plugins, check requirements and then initialize
  // ones that require it.
  ksort($plugins);
  foreach ($plugins as $plugins_info) {
    foreach ($plugins_info as $info) {
      if (array_key_exists('requires', $info)) {
        $met = false;
        foreach ($info['requires'] as $requirement) {
          foreach ($plugins as $check_info) {
            foreach ($check_info as $check) {
              if ($check['name'] == $requirement) {
                $met = true;
              }
            }
          }
        }
        
        if (!$met) {
          trigger_error("Required plugin {$requirement} is not found but is needed for {$info['name']}.", E_USER_ERROR);
        }
        unset($requirement);
        unset($met);
      }
      if (array_key_exists('initialize', $info) && function_exists($info['initialize'])) {
        $info['initialize']();
      }
    }
  }
  unset($plugins_info);
  unset($info);
}

if (!isset($output)) {
  trigger_error('$output variable was not defined!', E_USER_ERROR);
}

if (!function_exists('render')) {
  trigger_error('render() function was not defined!', E_USER_ERROR);
}

/** End of YAPF */
// Below is the default output of the framework
?>
<html>
  <?= render($output) ?>
</html>
