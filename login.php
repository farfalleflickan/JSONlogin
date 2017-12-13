<?php
if (isset($_POST["usrName"]) && isset($_POST["usrPwd"]) && isset($_POST["phpMode"])) {
    $usrname = $_POST["usrName"];
    $usrpwd = $_POST["usrPwd"];
    $phpMode = $_POST["phpMode"];
	$reqIp = $_SERVER['REMOTE_ADDR'];

	$myfile = file(".access_db");
	$myfile = array_reverse($myfile);
	$strikes = 0;
	foreach ($myfile as $myline){
		if ($myline[0] != "\n"){
			$resultArray = explode("\t", $myline);
			$reqTime=time()-(int)$resultArray[0];
			$nameStr=(string)$resultArray[1];
			$statusStr=(bool)$resultArray[2];
			if ($reqTime < 900 && strcmp($usrname, $nameStr) == 0 && $statusStr == 1){
				$strikes++;
			}
		}
	}
	if ($strikes >= 3){
		usleep(500000);
		echo "1";
		return;
	}

    $pass = password_hash($usrpwd, PASSWORD_DEFAULT);
    $mystr = $usrname . ":" . $pass;

    if ($phpMode == 0) {
        $file_handle = fopen(".user_db", "r") or die("Unable to open user database!");
        $myOutput = "1";
		$loginSuccess="false";
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            $myArr = explode(":", $line);
            if (isset($myArr[1])) {
                $hash = preg_replace('/\s+/', '', $myArr[1]);
                if (password_verify($usrpwd, $hash)) {
					$mySessionName=bin2hex(openssl_random_pseudo_bytes(16));
					session_start();
                    $_SESSION['login_user'] = $mySessionName;
					setcookie('session_key_active','1');
                    $myOutput = "0";
					$loginSuccess="true";
                    break;
                }
            }
        }
        fclose($file_handle);
		$myline = time()."\t".$usrname."\t".$loginSuccess."\t".$reqIp."\n";
		file_put_contents(".access_db", $myline, FILE_APPEND);
        echo $myOutput;
    } else if ($phpMode == 1) {
		$myline = $mystr."\n";
		file_put_contents(".toAddDb", $myline, FILE_APPEND);
		echo "2";
    }
} else {
    echo "-1";
}
?>
