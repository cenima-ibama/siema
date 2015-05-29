<?php

  defined("TMP_PATH")
  || define("TMP_PATH", str_replace("\\", "/", realpath(__DIR__ . "/uploads/") . '/'));

  $path = $_GET["path"];
  $path = str_replace("../","",$path);

  $scan = scandir(TMP_PATH  . $path);

  foreach ($scan as $key => $value) {
    echo $value . '<br />';
  }

  return true;

?>