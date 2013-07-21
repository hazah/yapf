<?php
/*
    yapf: Yet Another PHP Framework
    Copyright (C) 2013 Ivgeni Slabkovski

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see {http://www.gnu.org/licenses/}.
*/

/**
 * @file
 *
 * This is the front controller.
 *
 * This script makes three assumptions:
 * 1. The './plugins' directory exists.
 * 2. The $output global variable exists.
 * 3. The render() function exists.
 */

function yapf($plugin_dir = './plugins') {
  $plugins = array();

  if ($handle = opendir($plugin_dir)) {

    while (false !== ($entry = readdir($handle))) {
      if ($entry != "." && $entry != "..") {
        // Each plugin file could return plugin information. If so, record it.
        if (false === ($info = require_once $plugin_dir . '/' . $entry)) {
          trigger_error("Error requiring plugin file {$entry}.", E_USER_ERROR);
        }

        if (is_callable($info)) {
          $info = $info();
        }

        if (is_array($info)) {
          $info = (object) $info;
        }

        // The weight ensures proper initialzation order of all plugins.

        // Initialize $info for plugins that do not return config data.
        if (!is_object($info)) {
          // The default weight for API plugins is lower to include them first.
          $info = (object) array('weight' => -10);
        }
          // Set defaults
        if (!isset($info->weight)) {
          $info->weight = 0; // Default, non API weight
        }

        if (!isset($info->name)) {
          $info->name = pathinfo($entry, PATHINFO_FILENAME);
        }

        // Allow multiple plugins to share a weight. These will be ordered by the
        // way they were read from the filesystem using readdir().
        $plugins[$info->weight][] = $info;
      }

    }
    closedir($handle);

    // Sort the plugins, and flatten the plugins array.
    $plugins_info = $plugins;
    $plugins = array();

    ksort($plugins_info);

    foreach ($plugins_info as $plugin_set) {
      foreach ($plugin_set as $plugin) {
        $plugins[$plugin->name] = $plugin;
      }
    }

    foreach ($plugins as $plugin) {

      // check requirements
      if (isset($plugin->requires)) {

        $met = false;
        foreach ($plugin->requires as $requirement) {

          if (is_string($requirement)) {
            foreach (array_keys($plugins) as $check) {
              $met = (($check == $requirement) || $met);
            }
          }
          elseif (is_callable($requirement)) {
            list($met, $requirement) = call_user_func($requirement, $plugins);
          }

        }

        if (!$met) {
          trigger_error("Required plugin {$requirement} is not found but is needed for {$plugin->name}.", E_USER_ERROR);
        }

      }

      // initialize the ones that initialzation.
      if (isset($info->initialize) && is_callable($info->initialize)) {
        call_user_func($info->initialize);
      }

    }

  }

  // Provide defaults so that errors aren't necessary.
  global $output;
  if (!isset($output)) {
    $output = 'No plugin set variable $output!';
  }

  if (!function_exists('render')) {
    function render($output) {
      return is_array($output) ?
        array_reduce($output,

          function ($output, $item) {
            return $output . render($item);
          }

        ):
        $output;
    }
  }

  return $plugins;
}

// Below is the output of the framework ... edit, for this is your code now!
// Or delete it all and include this file elsewhere.
global $plugins;
$plugins = yapf();
?>
<!DOCTYPE html>
<html>
  <?= render($output) ?>
</html>
