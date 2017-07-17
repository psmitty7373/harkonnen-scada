<?php
	error_reporting (E_ALL ^ E_NOTICE);
        ini_set('session.cookie_lifetime', 60 * 60 * 24);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24);
        session_name("scada");
        session_start();
        if (isset($_POST['logout'])) {
		$_SESSION = array();
                session_destroy();
        }
?>
