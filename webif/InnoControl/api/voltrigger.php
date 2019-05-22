<?php
if($_GET['mode'] == 'trigger') {
  $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(" ", "_", $_GET['name']) . ".txt", "r");
  if ($file && (($line = fgets($file)) !== false)) {
    echo $line;
    if(trim($line) == 'u') {
      $status = 'd';
    } else {
      $status = 'u';
    }
    fclose($file);
  } else {
    $status = 'u';
  }
  echo $status;

  $pid = exec("./voltrigger.sh " . $_GET['mac'] . " " . $status . " > /dev/null 2>&1 & echo $!");
  echo $pid;
  $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(" ", "_", $_GET['name']) . ".txt", "w");
  fwrite($file, $status . "\n" . $pid);
  fclose($file);
} else if ($_GET['mode'] == 'stop') {
  $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(" ", "_", $_GET['name']) . ".txt", "r");
  $i = 0;
  while ($file && (($line = fgets($file)) !== false) && $i < 2) {
    if($i == 1) {
      $pid = $line;
    }
    $i = $i + 1;
  }
  fclose($file);
  if(isset($pid)) {
    exec("kill " . $pid);
  }
} else if ($_GET['mode'] == 'reset') {
    //stop voltrigger
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(" ", "_", $_GET['name']) . ".txt", "r");
    $i = 0;
    while ($file && (($line = fgets($file)) !== false) && $i < 2) {
      if($i == 1) {
        $pid = $line;
      }
      $i = $i + 1;
    }
    fclose($file);
    if(isset($pid)) {
      exec("kill " . $pid);
    }

    //reset flag
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(" ", "_", $_GET['name']) . ".txt", "w");
    fwrite($file, "d\n");
    fclose($file);
}
?>
