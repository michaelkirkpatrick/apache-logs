<?php
// Import Access Log

// Autoload Classes
spl_autoload_register(function ($class_name) {
	require_once  '/path/to/class/folder' . $class_name . '.class.php';
});

// Required Variables
$accessLog = new AccessLog();
$errorMsg = '';

//echo "Import Access Log\n";
//echo "Importing data (v1.2)\n";
//echo 'Started at: ' . date('g:i:s A', time()) . "\n";
$i = 1;

// Loop through file
$handle = @fopen("/path/to/access/log/access.log", "r");
if($handle){
	while(($log = fgets($handle, 4096)) !== false) {
		if(!empty($log)){
			// ----- Regular Expression Matching -----
			// http://stackoverflow.com/questions/7603017/parse-apache-log-in-php-using-preg-match
			$regex = '/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) "([^"]*)" "([^"]*)"$/';
			preg_match($regex ,$log, $matches);

			// Add to Database
			if(!empty($matches)){
				$accessLog->addEntry($matches);
				if($accessLog->error){
					$errorMsg = $accessLog->errorMsg;
					break;
				}else{
					$i++;
				}
			}else{
				echo "[Access Log Import Error] Unable to match: $log\n";
			}
		}
	}
	if(!feof($handle)){
		echo "[Access Log Import Error] unexpected fgets() fail\n";
	}
	fclose($handle);
}

// Close
if(!empty($errorMsg)){
	echo "[Access Log Import Error] $errorMsg\n";
}
$imported = number_format($i);
//echo 'Ended at: ' . date('g:i:s A', time()) . "\n";
//echo "Import complete.\n$imported entries imported.\n";
?>