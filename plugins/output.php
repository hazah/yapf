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
 * This API plugin helps with creation of renderable arrays for output.
 */
function output($plugin, $type, $settings) {
  global $output, $output_content;

  if (empty($output)){
    $output = array();
  }

  $settings += array(
    'output_content' => '',
    'format' => null,
  );

  extract($settings);

  do_action("output_{$plugin}_{$type}_alter", $plugin, $type, $format);
  do_action("output_{$plugin}_alter", $plugin, $type, $format);

  $output[$plugin][$type][] = $content;
}
