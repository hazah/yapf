<?php
/**
 * @file
 *
 * This is the front controller.
 *
 * This script makes three assumptions:
 * 1. The '../plugins' directory exists.
 * 2. The $output global variable exists.
 * 3. The render() function exists.
 */

/** YAPF Begins here */
if ($handle = opendir('./plugins')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      // Each plugin file could return plugin information. If so, record it.
      if (false === ($info = require_once './plugins/' . $entry)) {
        trigger_error("Error requiring plugin file {$entry}.", E_USER_ERROR);
      }

      // NOTE: The weight ensures proper initialzation order of all plugins.

      // Initialize $info for plugins that do not return config data.
      if (!is_array($info)) {
        // The default weight for API plugins is lower to include them first.
        $info = array('weight' => -10);
      }
        // Set defaults
      $info += array(
        'weight' => 0, // Default, non API weight
        'name' => str_replace('.php', '', $entry),
      );

      // Allow multiple plugins to share a weight. These will be ordered by the
      // way they were read from the filesystem using readdir().
      $plugins[$info['weight']][] = $info;
    }
  }
  closedir($handle);
  unset($handle);
  unset($entry);

  // Sort the plugins, check requirements and then initialize the ones that
  // need initialzation.

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

// Provide defaults so that errors aren't necessary.
if (!isset($output)) {
  trigger_error('$output variable was not defined!', E_USER_NOTICE);
  $output = '';
}

if (!function_exists('render')) {
  trigger_error('render() function was not defined!', E_USER_NOTICE);
  function render($output) {
    return $output;
  }
}

/** End of YAPF */

// Below is the output of the framework ... edit, for this is your template!
?>
<html>
  <?= render($output) ?>
</html>
