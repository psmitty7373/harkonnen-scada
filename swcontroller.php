<?php
	include "header.php";
	include "sql_include.php";
	if (!isset($_SESSION['logged_in']))
		header("location: swlogin.php");
	if (isset($_GET['submit'])) {
		if ($_SESSION['access_level'] == 100) {
			$access_level = $conn->real_escape_string($_GET['access_level']);
			if ($access_level > 25)
				$access_level = 25;
			$q = "UPDATE shield_wall_users SET access_level = " . $access_level . " WHERE username = '" . $conn->real_escape_string($_GET['username']) . "'";
			$r = $conn->query($q);
		}
	}
	$q = "SELECT * FROM shield_wall_status WHERE user_id = " . $_SESSION['logged_in_as'];
	$r = $conn->query($q);
	$row = $r->fetch_assoc();
?>
<html>
<head>
<title>Shield-wall Controller</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<?php 
if (isset($_SESSION['logged_in']) && $_SESSION['access_level'] > 0) {
?>
<link href="css/jquery-wijmo.css" rel="stylesheet" type="text/css">
<script src="js/batt2_debug.js" type="text/javascript"></script>
<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.10.4.min.js" type="text/javascript"></script>
<script src="js/raphael.js" type="text/javascript"></script>
<script src="js/jquery.wijmo.wijchartcore.js" type="text/javascript"></script>
<script src="js/jquery.wijmo.wijgauge.js" type="text/javascript"></script>
<script src="js/jquery.wijmo.wijradialgauge.js" type="text/javascript"></script>
<script src="js/jquery.wijmo.wijlineargauge.js" type="text/javascript"></script>
<script type="text/javascript">
        var popupStatus = 0;
	var globNum = 0;
        function loadPopup() {
                if(popupStatus == 0) { // if value is 0, show popup
                        $("#popup").fadeIn(0500); // fadein popup div
                        $("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
                        $("#backgroundPopup").fadeIn(0001);
                        popupStatus = 1; // and set value to 1
                }
        }
        function disablePopup() {
                if(popupStatus == 1) { // if value is 1, close popup
                        $("#popup").fadeOut("normal");
                        $("#backgroundPopup").fadeOut("normal");
                        popupStatus = 0;  // and set value to 0
                }
        }
	<?php echo 'var switchon = new Array(' . $row['batt1'] . ', ' . $row['batt2'] . ', ' . $row['batt3'] . ', ' . $row['batt4'] . ', ' . $row['cap1'] . ', ' . $row['wall'] . ');'; ?>
	var labels = new Array('batt1', 'batt2', 'batt3', 'batt4', 'cap1', 'wall');
	var autoscroll = 1;
	function switchclick(num) {
		document.getElementById("num").value = num;
		globNum = num;
		if (num >= 0 && num < 4) {
			document.getElementById("popup_prompt").innerHTML = "Attempting to toggle " + labels[num] + ". Enter access code to toggle device.";
			document.getElementById("code").value = "";
			document.getElementById("ip").style.display = "none";
                        document.getElementById("send_ip").style.display = "none";
		}
		if (num == 4) {
			document.getElementById("popup_prompt").innerHTML = "Attempting to toggle " + labels[num] + ". Input AUTH SVR IP address.";
			document.getElementById("ip").value = "10.10.73.174:881"
			document.getElementById("ip").style.display = "inline";
			document.getElementById("send_ip").style.display = "inline";
			document.getElementById("code").value = "Manual Override";
			document.getElementById("code").style.display = "none";
			document.getElementById("toggle").style.display = "none";
		}
		if (num == 5) {
			document.getElementById("popup_prompt").innerHTML = "WARNING!! WARNING!! WARNING!! WARNING!! WARNING!! WARNING!! WARNING!!<br><br>THIS ACTION WILL CHANGE OVERALL SHIELD STATUS!!<br><br>WARNING!! WARNING!! WARNING!! WARNING!! WARNING!! WARNING!! WARNING!!<br><br>Attempting to toggle " + labels[num] + ". Action will be authenticated.";
                        document.getElementById("ip").value = "10.10.73.175:881"
                        document.getElementById("ip").style.display = "inline";
			document.getElementById("ip").disabled = true;
                        document.getElementById("send_ip").style.display = "inline";
                        document.getElementById("code").value = "Manual Override";
                        document.getElementById("code").style.display = "none";
                        document.getElementById("toggle").style.display = "none";
		}
		document.getElementById("popup_resp").innerHTML = "";
		loadPopup(labels[num]);
		// DEBUG
		// battery 1 toggle test security lock-down
		// remove when shield-wall controller is connected to the planetary network
		if (code == '892e9c2054a7296ba3ac0ec0c1df90fcc9d35af8')
			alert('DEBUG: Switch 0 toggled.');
		// DEBUG
	}
	function switchupdate(num) {
		if (switchon[num] == 1) {
			switchon[num] = 0;
			document.getElementById("switch" + num).src = "images/switchoff.png";
			document.getElementById("img_" + labels[num]).src = "images/" + labels[num] + "off.png";
			if (num < 4) {
				$("#" + labels[num]).wijlineargauge("option", "face").style.fill='#ff0000';
				$("#" + labels[num]).wijlineargauge("redraw");
			}
		}
		else {
			switchon[num] = 1;
			document.getElementById("switch" + num).src = "images/switchon.png";
			document.getElementById("img_" + labels[num]).src = "images/" + labels[num] + "on.png";
			if (num < 4) {
				$("#" + labels[num]).wijlineargauge("option", "face").style.fill='#c2b38d';
				$("#" + labels[num]).wijlineargauge("redraw");
			}
			else if (num == 4) {
			}
		}
	}
	$(document).ready(function () {
		$(function() {
			$(".send_ip").click(function() {
				var ip = $("input#ip").val();
				if (globNum == 4)
					tgt = 'scada-cgi/cap1chalresp.py';
				else if (globNum == 5)
					tgt = 'scada-cgi/swchalresp.py';
				$.ajax({
					url: tgt,
					type: 'GET',
					data: {
						ip: ip
					},
					success: function(data) {
						document.getElementById("popup_resp").innerHTML = data;
						document.getElementById("code").style.display = "inline";
						document.getElementById("toggle").style.display = "inline";
						document.getElementById("cancel").style.display = "inline";
					}
				});
			});
			$(".toggle").click(function() {
				var code = $("input#code").val();
				var num = $("input#num").val();
				var i = $(this).attr("id").split("_");
				if (code != '' && code != null) {
					$.ajax({
						url: 'swupdate.php',
						type: 'POST',
						data: {
							action: 'update',
							code: code,
							num: num
						},
						success: function(data) {
							document.getElementById("popup_resp").innerHTML = data;
						}
					});
				}
			});
			$(".cancel").click(function() {
				disablePopup();
			});
		});
		$(function() {
			setInterval(function() {
				$.ajax({
					url: 'swupdate.php',
					type: 'POST',
					data: { action: 'getstatus' },
					dataType: 'json',
					success: function(data) {
						if (switchon[0] != data['batt1'])
							switchupdate(0);
						if (switchon[1] != data['batt2'])
							switchupdate(1);
						if (switchon[2] != data['batt3'])
							switchupdate(2);
						if (switchon[3] != data['batt4'])
							switchupdate(3);
						if (switchon[4] != data['cap1'])
							switchupdate(4);
						if (switchon[5] != data['wall'])
							switchupdate(5);
					}
				});
			}, 200);
		});
		// Shield-wall gauge
		$("#wall").wijradialgauge({
			radius: "auto",
			width: 300,
			height: 300,
			value: 50,
			max: 100,
			min: 0,
			startAngle: 300,
			sweepAngle: 300,
			labels: {
				style: {
						"font-size": "12pt",
						fill: "#000",
						stroke: "none"
				},
				offset: 36
			},
			pointer: {
				length: 0.8,
				width: 4,
				style: { fill: "180-rgb(255,255,255)", stroke: "rgb(255,255,255)", "stroke-width": "1.5" }
			},
			cap: {
				style: {
				fill: "270-#777d8d-#555b6b",
				stroke: "#555b6b"
				}
			},
			tickMajor: {
				factor: 3,
				offset: 39,
				position: "inside",
				style: { fill: "#555b6b", stroke: "#555b6b", "stroke-width": "4" }
			},
			tickMinor: {
				visible: true,
				offset: 44,
				interval: 2,
				position: "inside",
				style: { fill: "#606779", stroke: "#606779", "stroke-width": "1.5" }
			},
			face: {
				style: {},
				template: function (ui) {
					var set = ui.canvas.set();
					var circle = ui.canvas.circle(ui.origin.x, ui.origin.y, ui.r);
					circle.attr({ "stroke": "#000000", "stroke-width": 1, fill: "#a24920" });
					set.push(circle);
					var circle2 = ui.canvas.circle(ui.origin.x, ui.origin.y, ui.r - 14);
					circle2.attr({ "stroke": "#000000", "stroke-width": 1, fill: "c2b38d" });
					set.push(circle2);
					return set;
				}
			},
			ranges: [{
				startWidth: 10,
				endWidth: 10,
				startValue: 0,
				endValue: 100,
				startDistance:0.56,
				endDistance: 0.56,
				style: {
					fill: "#555b6b", stroke: "none"
				}
			}, {
				startWidth: 10,
				endWidth: 10,
				startValue: 0,
				endValue: 20,
				startDistance: 0.50,
				endDistance: 0.50,
				style: {
					fill: "rgb(255,0,0)", stroke: "rgb(255,0,0)", "stroke-width": "1.5"
				}
			}, {
				startWidth: 10,
				endWidth: 10,
				startValue: 20,
				endValue: 90,
				startDistance: 0.50,
				endDistance: 0.50,
				style: {
					fill: "rgb(248,255,0)", stroke: "rgb(248,255,0)", "stroke-width": "1.5"
				}
			}, {
				startWidth: 10,
				endWidth: 10,
				startValue:90,
				endValue: 100,
				startDistance: 0.50,
				endDistance: 0.50,
				style: {
					fill: "rgb(0,255,0)", stroke: "rgb(0,255,0)", "stroke-width": "1.5"
				}
			}]
		});

		//Main capacitor gauge
		$("#cap1").wijradialgauge({
			radius: "auto",
			width: 300,
			height: 300,
			value: 50,
			max: 100,
			min: 0,
			startAngle: 300,
			sweepAngle: 300,
			labels: {
				style: {
						"font-size": "12pt",
						fill: "#000",
						stroke: "none"
				},
				offset: 36
			},
			pointer: {
				length: 0.8,
				width: 4,
				style: { fill: "180-rgb(255,255,255)", stroke: "rgb(255,255,255)", "stroke-width": "1.5" }
			},
			cap: {
				style: {
				fill: "270-#777d8d-#555b6b",
				stroke: "#555b6b"
				}
			},
			tickMajor: {
				factor: 3,
				offset: 39,
				position: "inside",
				style: { fill: "#555b6b", stroke: "#555b6b", "stroke-width": "4" }
			},
			tickMinor: {
				visible: true,
				offset: 44,
				interval: 2,
				position: "inside",
				style: { fill: "#606779", stroke: "#606779", "stroke-width": "1.5" }
			},
			face: {
				style: {},
				template: function (ui) {
					var set = ui.canvas.set();
					var circle = ui.canvas.circle(ui.origin.x, ui.origin.y, ui.r);
					circle.attr({ "stroke": "#000000", "stroke-width": 1, fill: "#a24920" });
					set.push(circle);
					var circle2 = ui.canvas.circle(ui.origin.x, ui.origin.y, ui.r - 14);
					circle2.attr({ "stroke": "#000000", "stroke-width": 1, fill: "c2b38d" });
					set.push(circle2);
					return set;
				}
			},
			ranges: [{
				startWidth: 10,
				endWidth: 10,
				startValue: 0,
				endValue: 100,
				startDistance:0.56,
				endDistance: 0.56,
				style: {
					fill: "#555b6b", stroke: "none"
				}
			}, {
				startWidth: 10,
				endWidth: 10,
				startValue: 0,
				endValue: 20,
				startDistance: 0.50,
				endDistance: 0.50,
				style: {
					fill: "rgb(255,0,0)", stroke: "rgb(255,0,0)", "stroke-width": "1.5"
				}
			}, {
				startWidth: 10,
				endWidth: 10,
				startValue: 20,
				endValue: 90,
				startDistance: 0.50,
				endDistance: 0.50,
				style: {
					fill: "rgb(248,255,0)", stroke: "rgb(248,255,0)", "stroke-width": "1.5"
				}
			}, {
				startWidth: 10,
				endWidth: 10,
				startValue:90,
				endValue: 100,
				startDistance: 0.50,
				endDistance: 0.50,
				style: {
					fill: "rgb(0,255,0)", stroke: "rgb(0,255,0)", "stroke-width": "1.5"
				}
			}]
		});

		$("#batt1").wijlineargauge({
			value: 0,
			width: 200,
			animation: {enabled: true, duration: 500, easing: ">"},
			labels: {
				style: {
					fill: "#000000",
					"font-size": "12pt",
					"font-weight": "800"
				}
			},
			tickMajor: {
				position: "inside",
				interval: 20,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			tickMinor: {
				position: "inside",
				visible: true,
				interval: 4,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			pointer: {
				shape: "rect",
				length: 0.5,
				style: {
					fill: "#ffffff",
					stroke: "#ffffff"
				}
			},
			face: {
				style: {
					<?php if ($row['batt1'] == 1) { echo ' fill: "#c2b38d",'; } else { echo ' fill: "#ff0000",'; } ?>
					stroke: "#a24920",
					"stroke-width": "8"
				}
			}
		});

		$("#batt2").wijlineargauge({
			value: 0,
			width: 200,
			animation: {enabled: true, duration: 500, easing: ">"},
			labels: {
				style: {
					fill: "#000000",
					"font-size": "12pt",
					"font-weight": "800"
				}
			},
			tickMajor: {
				position: "inside",
				interval: 20,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			tickMinor: {
				position: "inside",
				visible: true,
				interval: 4,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			pointer: {
				shape: "rect",
				length: 0.5,
				style: {
					fill: "#ffffff",
					stroke: "#ffffff"
				}
			},
			face: {
				style: {
					<?php if ($row['batt2'] == 1) { echo ' fill: "#c2b38d",'; } else { echo ' fill: "#ff0000",'; } ?>
					stroke: "#a24920",
					"stroke-width": "8"
				}
			}
		});

		$("#batt3").wijlineargauge({
			value: 0,			
			width: 200,
			animation: {enabled: true, duration: 500, easing: ">"},
			labels: {
				style: {
					fill: "#000000",
					"font-size": "12pt",
					"font-weight": "800"
				}
			},
			tickMajor: {
				position: "inside",
				interval: 20,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			tickMinor: {
				position: "inside",
				visible: true,
				interval: 4,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			pointer: {
				shape: "rect",
				length: 0.5,
				style: {
					fill: "#ffffff",
					stroke: "#ffffff"
				}
			},
			face: {
				style: {
					<?php if ($row['batt3'] == 1) { echo ' fill: "#c2b38d",'; } else { echo ' fill: "#ff0000",'; } ?>
					stroke: "#a24920",
					"stroke-width": "8"
				}
			}
		});

		$("#batt4").wijlineargauge({
			value: 0,
			width: 200,
			animation: {enabled: true, duration: 500, easing: ">"},
			labels: {
				style: {
					fill: "#000000",
					"font-size": "12pt",
					"font-weight": "800"
				}
			},
			tickMajor: {
				position: "inside",
				interval: 20,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			tickMinor: {
				position: "inside",
				visible: true,
				interval: 4,
				style: {
					fill: "#1E395B",
					stroke: "none"
				}
			},
			pointer: {
				shape: "rect",
				length: 0.5,
				style: {
					fill: "#ffffff",
					stroke: "#ffffff"
				}
			},
			face: {
				style: {
					<?php if ($row['batt4'] == 1) { echo ' fill: "#c2b38d",'; } else { echo ' fill: "#ff0000",'; } ?>
					stroke: "#a24920",
					"stroke-width": "8"
				}
			}
		});
		
		var timer1=setInterval(function(){update_gauges()},500);

		function update_gauges() {
			var rand1 = parseInt((Math.random() * 10) + 90);
			var rand2 = parseInt((Math.random() * 10) + 90);
			var rand3 = parseInt((Math.random() * 10) + 90);
			var rand4 = parseInt((Math.random() * 10) + 90);
			var rand5 = parseInt((Math.random() * 10));
			var val5 = (((rand1 * switchon[0]) + (rand2 * switchon[1]) + (rand3 * switchon[2]) + (rand4 * switchon[3]))/4);
			if (val5 == 0)
				val5 = rand5;
			var val7 = parseInt((Math.random() * 10) + 90) - ((100 - val5)/2);
			var val6 = parseInt((Math.random() * 10) + 90) * switchon[5];

			if (switchon[5] == 1) {
				$("#wall").wijradialgauge("option", "value", val7);
			}
			else {
				$("#wall").wijradialgauge("option", "value", 0);
			}
			if (switchon[4] == 1) {
				$("#cap1").wijradialgauge("option", "value", val5);
			}
			else {
				$("#cap1").wijradialgauge("option", "value", 0);
			}
			if (switchon[3] == 1) {
				$("#batt4").wijlineargauge("option", "value", rand4);
			}
			else {
				$("#batt4").wijlineargauge("option", "value", 0);
			}
			if (switchon[2] == 1) {
				$("#batt3").wijlineargauge("option", "value", rand3);
			}
			else {
				$("#batt3").wijlineargauge("option", "value", 0);
			}
			if (switchon[1] == 1) {
				$("#batt2").wijlineargauge("option", "value", rand2);
			}
			else {
				$("#batt2").wijlineargauge("option", "value", 0);
			}
			if (switchon[0] == 1) {
				$("#batt1").wijlineargauge("option", "value", rand1);
			}
			else {
				var temp = $("#bat1cap").wijlineargauge("option", "face").style;
				$("#batt1").wijlineargauge("option", "value", 0);
			}
		}
	});
</script>
</head>
<body>
<center>
<img style="float: left;" src="images/shieldlogo_sm.png"><br><br>
<div id="popup" class="popup"> 
	<div class="popup_content">
		<img src="images/warningtop.jpg"><br><br>
		<span id="popup_prompt"></span><br>
		<input type="text" name="ip" id="ip" style="display:none;">
		<button type="button" class="send_ip" id="send_ip" style="display:none;">Send</button>
		<input type="text" name="code" id="code">
		<input type="hidden" name="num" id="num">
		<button type="button" class="toggle" id="toggle">Toggle</button>
		<button type="button" class="cancel" id="cancel">Close</button><br>
		Message:<br><div id="popup_resp" style="background-color:#ddd;"></div>
		<br><br>
		<br><img src="images/warningbot.jpg">
	</div>
</div>
<div class="loader"></div>
<div id="backgroundPopup"></div>
<div style="width: 100%; overflow:hidden;">
<div style="width: 62%; float: left; padding-bottom: 10px; min-width:854px;">
<table style="width: 854px; float: right; margin: 10px;">
<tr><th colspan="3">Shield-wall Main Control Panel</th></tr>
<tr><td><div id="wall" class="ui-corner-all"></div>Shield Strength (%)</td><td><div id="cap1" class="ui-corner-all"></div>Main Capacitor Charge (%)</td><td><div id="batt1" class="ui-corner-all"></div>Battery 1 Charge (%)<br><div id="batt2" class="ui-corner-all"></div>Battery 2 Charge (%)<div id="batt3" class="ui-corner-all"></div>Battery 3 Charge (%)<br><div id="batt4" class="ui-corner-all"></div>Battery 4 Charge (%)</td></tr>
<tr><td colspan="6">CSRF Flag: 0d9e036e1393cada8c27bf6369913efb43ba71c3</td></tr>
<tr><td colspan="6"><form method="GET" action="<?php echo $_SERVER['PHP_SELF'] ?>">Grant access to:<input type="text" name="username"> Access level:<input type="text" name="access_level"><input type="submit" name="submit" value="Grant"></form></td></tr>
</table>
</div>
<div style="width: 37%; float: right;">
<table style="width: 413px; float: left; margin: 10px;">
<tr><th>Shield-wall Schematic</th></tr>
<tr style="height:400px"><td><div style="height: 540px; overflow: auto; position: relative;">
<img src="images/paths.png" style="position: absolute; top: 5px; left: 15px;">
<?php
	if ($row['batt1'] == 1) {
		echo '<img id="switch0" src="images/switchon.png" onclick="switchclick(0)" style="position: absolute; top: 100px; left: 27px;">';
		echo '<img id="img_batt1" src="images/batt1on.png" style="position: absolute; top: 10px; left: 25px;">';
	}
	else {
		echo '<img id="switch0" src="images/switchoff.png" onclick="switchclick(0)" style="position: absolute; top: 100px; left: 27px;">';
                echo '<img id="img_batt1" src="images/batt1off.png" style="position: absolute; top: 10px; left: 25px;">';
	}
	if ($row['batt2'] == 1) {
                echo '<img id="switch1" src="images/switchon.png" onclick="switchclick(1)" style="position: absolute; top: 100px; left: 122px;">';
                echo '<img id="img_batt2" src="images/batt2on.png" style="position: absolute; top: 10px; left: 120px;">';
        }
        else {
                echo '<img id="switch1" src="images/switchoff.png" onclick="switchclick(1)" style="position: absolute; top: 100px; left: 122px;">';
                echo '<img id="img_batt2" src="images/batt2off.png" style="position: absolute; top: 10px; left: 120px;">';
        }
	if ($row['batt3'] == 1) {
                echo '<img id="switch2" src="images/switchon.png" onclick="switchclick(2)" style="position: absolute; top: 100px; left: 217px;">';
                echo '<img id="img_batt3" src="images/batt3on.png" style="position: absolute; top: 10px; left: 215px;">';
        }
        else {
                echo '<img id="switch2" src="images/switchoff.png" onclick="switchclick(2)" style="position: absolute; top: 100px; left: 217px;">';
                echo '<img id="img_batt3" src="images/batt3off.png" style="position: absolute; top: 10px; left: 215px;">';
        }
	if ($row['batt4'] == 1) {
                echo '<img id="switch3" src="images/switchon.png" onclick="switchclick(3)" style="position: absolute; top: 100px; left: 312px;">';
                echo '<img id="img_batt4" src="images/batt4on.png" style="position: absolute; top: 10px; left: 310px;">';
        }
        else {
                echo '<img id="switch3" src="images/switchoff.png" onclick="switchclick(3)" style="position: absolute; top: 100px; left: 312px;">';
                echo '<img id="img_batt4" src="images/batt4off.png" style="position: absolute; top: 10px; left: 310px;">';
        }
	if ($row['cap1'] == 1) {
                echo '<img id="switch4" src="images/switchon.png" onclick="switchclick(4)" style="position: absolute; top: 315px; left: 173px;">';
                echo '<img id="img_cap1" src="images/cap1on.png" style="position: absolute; top: 220px; left: 147px;">';
        }
        else {
                echo '<img id="switch4" src="images/switchoff.png" onclick="switchclick(4)" style="position: absolute; top: 315px; left: 173px;">';
                echo '<img id="img_cap1" src="images/cap1off.png" style="position: absolute; top: 220px; left: 147px;">';
        }
	if ($row['wall'] == 1) {
                echo '<img id="switch5" src="images/switchon.png" onclick="switchclick(5)" style="position: absolute; top: 432px; left: 263px;">';
                echo '<img id="img_wall" src="images/wallon.png" style="position: absolute; top: 419px; left: 139px;">';
        }
        else {
                echo '<img id="switch5" src="images/switchoff.png" onclick="switchclick(5)" style="position: absolute; top: 100px; left: 27px;">';
                echo '<img id="img_wall" src="images/walloff.png" style="position: absolute; top: 419px; left: 139px;">';
        }
?>
</div></td></tr>
</table>
</div>
</div>
<?php
if (isset($_SESSION['logged_in'])) {
?>
<br>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="submit" name="logout" value="Logout">
</form>
<?php } ?>
</center>
<?php } else { ?>
<body>
<center>
<strong>ACCESS DENIED</strong><br><br>
Either you are not logged in or your access level is too low to access this page.<br><br>
<a href="swlogin.php">Login</a><br>
</center>
<?php } ?>
(C) Arrakis Bank.
</body>
</html>
<?php include "footer.php"; ?>
