<?php

function page_title() {
  return 'Not Found';
}

function page_content() {
  return 'Page Not Found';
}


function page() {
  theme('page_title', array(
    'plugin' => 'page',
    'content' => page_title(),
  ));

  theme('page_content', array(
    'plugin' => 'page',
    'content' => page_content(),
  ));
}


if (!function_exists('theme_page')) {
  function theme_page($type, $format, $content) {
    switch ($type) {
      case 'page_title':
        $content = '<title>' . $content . '</title>';
        break;
      case 'page_content':
        $content = '<body>' . $content . '</body>';
        break;
    }
    return $content;
  }
}

return 10;
