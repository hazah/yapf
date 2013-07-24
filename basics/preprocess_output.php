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
 * This plugin helps with preprocessing of renderable arrays before output.
 */

// Plugin info:
// TODO: Include human readable information so that some other plugin can make
// use of that information.
return array(
  'initialize' => function () {
    // All we do is call this hook, and this will allow all other plugins to
    // preprocess the array.
    do_action('preprocess_output');
  },
  // Set the weight higher than the page plugin so that the initializer runs
  // after it.
  'weight' => 20,
  'requires' => array('actions', 'output'),
);
