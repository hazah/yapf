<?php
/**
 * @file
 *
 * This is the front controller.
 */

/** YAPF Begins here */
if ($handle = opendir('./plugins')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      // Each plugin file could return plugin information. If so, record it.
      if (false === ($info = require_once './plugins/' . $entry)) {
        trigger_error("Error requiring plugin file {$entry}.", E_USER_ERROR);
      }

      // Initialize $info for plugins that do not return config data.
      if (!is_array($info)) {
        $info = array();
      }
        // Set defaults
      $info += array(
        'weight' => 0,
        'name' => str_replace('.php', '', $entry),
      );
      // The weight ensures proper initialzation order of all plugins.
      $plugins[$info['weight']][] = $info;
    }
  }
  closedir($handle);
  unset($handle);
  unset($entry);

  // Sort the plugins, check requirements and then initialize the ones that
  // require it.

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

// FIXME: Provide sensible defaults so that errors aren't necessary.
if (!isset($output)) {
  trigger_error('$output variable was not defined!', E_USER_ERROR);
}

if (!function_exists('render')) {
  trigger_error('render() function was not defined!', E_USER_ERROR);
}

/** End of YAPF */

// Below is the output of the framework ... edit!
?>
<html>
  <?= render($output) ?>
</html>
