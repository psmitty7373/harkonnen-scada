<?php
	include "header.php";
	include "sql_include.php";
	if (isset($_POST['login'])) {
		$q = "SELECT * FROM shield_wall_users WHERE username = '" . $conn->real_escape_string($_POST['username']) . "'";
		$r = $conn->query($q);
		$row = $r->fetch_assoc();
		$salt = substr($row['password'],0,28);
		if (crypt($conn->real_escape_string($_POST['password']), $salt) ==  $row['password']) {
			$_SESSION['logged_in'] = true;
			$_SESSION['logged_in_as'] = $row['user_id'];
			$_SESSION['access_level'] = $row['access_level'];
			header("location: swcontroller.php");
                }
		else {
			$error = "Invalid username or password.";
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
<strong>APD Warning and Consent Banner</strong><br><br>
You are accessing an Arrakis Planetary Government (APG) Information System (IS) that is provided for APG-authorized use only.<br>
By using this IS (which includes any device attached to this IS), you consent to the following conditions:<br><br>
.The APG routinely intercepts and monitors communications on this IS for purposes including, but not limited to, penetration<br>
testing, COMSEC monitoring, network operations and defense, personnel misconduct (PM), law enforcement (LE), and<br>
counterintelligence (CI) investigations.<br><br>
.At any time, the APG may inspect and seize data stored on this IS.<br><br>
.Communications using, or data stored on, this IS are not private, are subject to routine monitoring, interception, and search,<br>
and may be disclosed or used for any USG authorized purpose.<br><br>
.This IS includes security measures (e.g., authentication and access controls) to protect USG interests--not for<br>
your personal benefit or privacy.<br><br>
.Notwithstanding the above, using this IS does not constitute consent to PM, LE or CI investigative searching or monitoring<br>
of the content of privileged communications, or work product, related to personal representation or services by attorneys,<br>
psychotherapists, or clergy, and their assistants. Such communications and work product are private and confidential.<br>
See User Agreement for details.<br><br>
<?php
if (isset($_SESSION['logged_in_as'])) {
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="submit" name="logout" value="Logout">
</form>
<?php } else { ?>
<form method="POST" action="<?PHP echo $_SERVER['PHP_SELF'] ?>">
<table style="width:300px;">
<tr><th>Shield-wall Control System Login</th></tr>
<?php if (isset($error)) { ?>
<tr><td style="text-align: center; color: #FF0000;"><?php echo $error ?></td></tr>
<?php } ?>
<tr><td style="text-align: center;">Username: <input type="text" value="" name="username"></td></tr>
<tr><td style="text-align: center;">Password: <input type="password" value="" name="password"></td></tr>
<tr><td style="text-align: center;"><input type="submit" name="login" value="Login"></td></tr>
</table>
</form>
<a href="register.php" target="_blank">New User</a><br><br>
For help contact <a href="scada-admin@arrakisdefense.com">scada_admin@arrakisdefense.com</a>.<br><br>
(C) Arrakis Bank.
<?php } ?>
</center>
</body>
</html>
<?php include "footer.php"; ?>
