<?php
	include "header.php";
	include "sql_include.php";
	if (isset($_POST['submit'])) {
                if ($_POST['password'] != $_POST['password2'])
                        $errormsg = $errormsg . "Passwords do not match.<br />";
                if ($_POST['username'] == '')
                        $errormsg = $errormsg . "Username cannot be blank.<br />";
                if ($_POST['password'] == '')
                        $errormsg = $errormsg . "Password cannot be blank.<br />";
                if (!isset($errormsg)) {
                        $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)),0,20);
                        $salt = str_replace('+', '.', $salt);
                        $hash = crypt($conn->real_escape_string($_POST['password']), '$2y$10$'.$salt.'$');
                        $q = "SELECT * FROM shield_wall_users WHERE username = '" . $conn->real_escape_string($_POST['username']) . "'";
                        $r = $conn->query($q);
                        $n = $r->num_rows;
                        if ($n == 0) {
                                $success = 1;
                                $q = "INSERT INTO shield_wall_users (username, password) VALUES ('" . $conn->real_escape_string($_POST['username']) . "', '" . $hash . "')";
                                $r = $conn->query($q);
				$insertid = $conn->insert_id;
				$q = "INSERT INTO shield_wall_status (batt1, batt2, batt3, batt4, cap1, wall, user_id) values (1,1,1,1,1,1," . $insertid . ")";
				$r = $conn->query($q);
                        }
                        else {
                                $errormsg = $errormsg . "Username already in use.";
                        }
                }
	}
?>
<html>
<head>
<title>Arrakis Shield-wall Control System</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<center>
<img src="images/shieldlogo.png"><br><br>
<?php
if (isset($_SESSION['logged_in_as'])) {
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="submit" name="logout" value="Logout">
</form>
<?php } else { ?>
<form method="POST" action="<?PHP echo $_SERVER['PHP_SELF'] ?>">
<table style="width:600px;">
<tr><th colspan="2">Shield-wall Control System Registration</th></tr>
<?php 
	if ($success == 1) {
?>
<tr><th colspan="2">User account created successfully.<br>Account is pending activation by administrator.</th></tr>
</table>
<?php } else { ?>
<?php if (isset($errormsg)) { ?>
<tr><td colspan="2" style="text-align: center; color: #FF0000;"><?php echo $errormsg ?></td></tr>
<?php } ?>
<tr><td>Username:</td><td><input type="text" name="username" size="40" value="<?php echo $_POST['username'] ?>"></td></tr>
<tr><td>Password:</td><td><input type="password" name="password" size="40"></td></tr>
<tr><td>Confirm:</td><td><input type="password" name="password2" size="40"></td></tr>
<tr><td colspan="2"><input type="submit" name="submit"></td></tr>
</table><br><br>
</form>
<?php } } ?>
<br><a href="swlogin.php">Return to Login</a>
</center>
</body>
</html>
<?php include "footer.php"; ?>
