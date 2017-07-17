<?php
        error_reporting (E_ALL ^ E_NOTICE);
        ini_set('session.cookie_lifetime', 60 * 60 * 24);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24);
        session_name("scada");
        session_start();
        include("sql_include.php");
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
                $q = "(SELECT * FROM shield_wall_log ORDER BY log_id DESC LIMIT 50) ORDER BY log_id ASC";
                $r = $conn->query($q);
                while ($row = $r->fetch_assoc()) {
                	echo '[' . $row['user'] . '] ' . $row['event_text'] . '<br>';
                }
        }
        $conn->close();
?>
