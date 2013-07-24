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
 * This plugin helps with creation of a complete page renderable array for output.
 */

// Plugin info:
// TODO: Include human readable information so that some other plugin can make
//       use of it.
return array(
  'initialize' => function () {
    add_action('set_page_title', function ($title) {
      global $page_title;

      $page_title = $title;
      do_action('page_title_alter');
    });

    add_action('set_page_content', function ($content) {
      global $page_content;

      $page_content = $content;
      do_action('page_content_alter');
    });

    add_action('add_page_js', function ($js) {
      global $page_js;

      $page_js[] = $js;
    });

    add_action('add_page_css', function ($css) {
      global $page_css;

      $page_css[] = $css;
    });

  },
  'requires' => array('actions'),
);
