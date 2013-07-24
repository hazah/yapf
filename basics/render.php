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
 * This API plugin helps with the transformation of renderable arrays into HTML.
 */
function render($content, $current_plugin = null) {
  global $plugins, $renderable;
  $output = '';

  if (is_array($content)) {
    foreach (array_keys($content) as $key) {
      $renderable = $content[$key];

      do_action("render_alter");

      if (is_string($key)) {
        // If the key is the name of a plugin, record it so that we can specify
        // how to properly alter the array.
        foreach ($plugins as $plugin) {

          if ($key == $plugin->name) {
            $output .= render($renderable, $key);

            // The render call above handled the plugin key, so we continue
            // to render the key that comes just after it.
            continue 2;
          }

        }

        // We're rendering within a specific plugin
        if ($current_plugin) {
          do_action("render_{$current_plugin}_{$key}_alter");
        }
      }

      // We're rendering within a specific plugin
      if ($current_plugin) {
        do_action("render_{$current_plugin}_alter");
      }

      $output .= render($renderable, $current_plugin);
    }
  }
  else {
    $output .= $content;
  }
  return $output;
}
