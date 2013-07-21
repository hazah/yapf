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
 * This API plugin enables a hook system.
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
