<?php

session_start();
if (isset($_POST["usrName"]) && isset($_POST["usrPwd"]) && isset($_POST["phpMode"])) {
    $usrname = $_POST["usrName"];
    $usrpwd = $_POST["usrPwd"];
    $phpMode = $_POST["phpMode"];
    $pass = password_hash($usrpwd, PASSWORD_DEFAULT);
    $mystr = $usrname . ":" . $pass;

    if ($phpMode == 0) {
        $file_handle = fopen(".user_db", "r");
        $myOutput = "1";
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            $myArr = explode(":", $line);
            if (isset($myArr[1])) {
                $hash = preg_replace('/\s+/', '', $myArr[1]);
                if (password_verify($usrpwd, $hash)) {
                    $_SESSION['login_user'] = bin2hex(openssl_random_pseudo_bytes(16));
                    $myOutput = "0";
                    break;
                }
            }
        }
        fclose($file_handle);
        echo $myOutput;
    } else if ($phpMode == 1) {
        echo $mystr;
    }
} else {
    echo "-1";
}
?>