<?php

$command = 'sh backup.sh';
 $result = shell_exec($command);
	echo $result;

?>