<?php
	function toggle($switch, $conn) {
		if ($switch >= 0 && $switch < 6) {
			$labels = Array('batt1', 'batt2', 'batt3', 'batt4', 'cap1', 'wall');
			$q = "SELECT * FROM shield_wall_status WHERE user_id = " . $_SESSION['logged_in_as'];
			$r = $conn->query($q);
			$row = $r->fetch_assoc();
			if ($row[$labels[$switch]] == 1)
				$q = "UPDATE shield_wall_status SET " . $labels[$switch] . " = 0 WHERE user_id = " . $_SESSION['logged_in_as'];
			else
				$q = "UPDATE shield_wall_status SET " . $labels[$switch] . " = 1 WHERE user_id = " . $_SESSION['logged_in_as'];	
			$r = $conn->query($q);
		}
	}

        error_reporting (E_ALL ^ E_NOTICE);
        ini_set('session.cookie_lifetime', 60 * 60 * 24);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24);
        session_name("scada");
        session_start();
        include("sql_include.php");
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
			$q = "SELECT * FROM shield_wall_status WHERE user_id = " . $_SESSION['logged_in_as'];
			$r = $conn->query($q);
			$row = $r->fetch_assoc();
			if ($_POST['action'] == 'update') {
				if ($_POST['code'] == '892e9c2054a7296ba3ac0ec0c1df90fcc9d35af8' && $_POST['num'] == 0) {
					toggle(0, $conn);
					echo 'Battery 1 status toggled.';
				}
				else if ($_POST['code'] == '60fcc63488d939bb6b79c3b02b8ad4496bd140b3' && $_POST['num'] == 1) {
					toggle(1, $conn);
					echo 'Battery 2 status toggled.';
				}
				else if ($_POST['code'] == '94899f6c9843c836e33b1cc253b5d6ee525e2a9d' && $_POST['num'] == 2) {
					toggle(2, $conn);
					echo 'Battery 3 status toggled.';
				}
				else if ($_POST['code'] == 'e98b1cdcc74347614e2f1898d32e927d4b85a3d7' && $_POST['num'] == 3) {
					toggle(3, $conn);
					echo 'Battery 4 status toggled.';
				}
				else if ($_POST['num'] == 4) {
					if ($row['cap1'] == 1) {
						if ($row['batt1'] == 0 && $row['batt2'] == 0 && $row['batt3'] == 0 && $row['batt4'] == 0) {
							if ($_POST['code'] == 'afbb47a77c41468db5387ba5a89498f3f1b74fe5') {
								toggle(4, $conn);
								echo 'Main capacitor toggled off.';
							}
							else
								echo 'Error!! Invalid code.';
						}
						else
							echo 'Error!! The main capacitor cannot be toggled off until all batteries are offline.';
					}
					else {
						if ($row['batt1'] == 0 && $row['batt2'] == 0 && $row['batt3'] == 0 && $row['batt4'] == 0)
							echo 'Error!! The main capacitor cannot be toggled on unless at least one battery is online.';
						else {
							if ($_POST['code'] == 'afbb47a77c41468db5387ba5a89498f3f1b74fe5') {
								toggle(4, $conn);
								echo 'Main capacitor toggled on.';
							}
							else
								echo 'Error!! Invalid code.';
						}
					}
				}
				else if ($_POST['num'] == 5) {
					if ($row['wall'] == 1) {
						if ($row['cap1'] == 0) {
							if ($_POST['code'] == 'f75e40d40d7d435a9e9bfb01f68cf496dc8a4c9f') {
                                                                toggle(5, $conn);
                                                                echo 'Shield-wall toggled off, thanks for dooming us all!.<br>680f9fa07f13d60a32c6ee62657af2aa1daadfff';
                                                        }
                                                        else
                                                                echo 'Error!! Invalid code.';
						}
						else
							echo 'Error!! The main capacitor cannot be toggled off until the main capacitor is offline.';
					}
					else {
						if ($row['cap1'] == 1) {
							if ($_POST['code'] == 'f75e40d40d7d435a9e9bfb01f68cf496dc8a4c9f') {
                                                                toggle(5, $conn);
                                                                echo 'Shield-wall toggled on, thanks for protecting the planet!.';
                                                        }
                                                        else
                                                                echo 'Error!! Invalid code.';
						}
					}
				}
				else
					echo "Error!! Invalid code.";
			}
			if ($_POST['action'] == 'getstatus' || $_GET['action'] == 'getstatus') {
				echo json_encode($row);
			}
        }
        $conn->close();
?>
