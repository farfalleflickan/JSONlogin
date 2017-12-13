<?php
session_start();
if (isset($_SESSION['login_user'])) {
    unset($_SESSION['login_user']);
	setcookie('session_key_active','1',1);
    echo "LOGGED OUT";
}
