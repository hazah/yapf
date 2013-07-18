<?php

if ($handle = opendir('./plugins')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      $weight = require_once './plugins/' . $entry;
      if ($weight !== false) {
        $plugins[$weight] = str_replace('.php', '', $entry);
      }
    }
  }
  closedir($handle);
  unset($handle);
  unset($entry);
  unset($weight);

  ksort($plugins);
  foreach ($plugins as $plugin) {
    $plugin();
  }
  unset($plugin);
}

if (isset($theme) && array_key_exists('page', $theme)) {
  extract($theme['page']);
  unset($theme);
}
else {
  $page_title = 'Failure';
  $page_content = '$theme variable was not set!';
}

?>
<html>
<head>
  <?= $page_title ?>
</head>
<?= $page_content ?>
</html>
