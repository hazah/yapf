<?php

if ($handle = opendir('./plugins')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      $info = require_once './plugins/' . $entry;
      if ($info !== false) {
        $info += array(
          'weight' => 0,
          'name' => str_replace('.php', '', $entry),
        );
        $plugins[$info['weight']] = $info;
      }
    }
  }
  closedir($handle);
  unset($handle);
  unset($entry);

  ksort($plugins);
  foreach ($plugins as $info) {
    if (array_key_exists('initialize', $info) && function_exists($info['initialize'])) {
      $info['initialize']();
    }
  }
  unset($info);
}

if (isset($theme) && array_key_exists('page', $theme)) {//print_r($theme);die;
  extract($theme);
  unset($theme);
}
else {
  $page_title = 'Failure';
  $page_content = '$theme variable was not set!';
}

?>
<html>
<head>
  <?= render($page['title']) ?>
</head>
<?= render(array('page' => $page['body'])) ?>
</html>
