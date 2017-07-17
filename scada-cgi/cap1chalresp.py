#!/usr/bin/env python
import socket
import cgi, cgitb

form = cgi.FieldStorage()

UDP_IP = form["ip"].value
UDP_IP = UDP_IP.split(":")[0]
UDP_PORT = 881

try:
	socket.inet_aton(UDP_IP)
	sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
	sock.sendto("c0662eb6e4", (UDP_IP, UDP_PORT))
	sock.sendto("518e1355fb", (UDP_IP, UDP_PORT))
	sock.sendto("b0183ec9a9", (UDP_IP, UDP_PORT))
	sock.sendto("fd461188db", (UDP_IP, UDP_PORT))

	print "Content-type: text/html"
	print

	print """
	MainCap1 Toggle Challenge/Response Auth Request v.1.0<br>
	<br>
	Initiating connection to:
	"""
	print UDP_IP
	print """
	<br>
	Handshake failed<br>
	<strong>Connection failed</strong><br>
	Attempting blind send challenge<br>
	Sending packet 1.... 2.... 3.... 4....<br>
	(4/4) packets sent successfully<br>
	Awaiting replies .... .... .... ....<br>
	<strong>Error! Timeout</strong><br>
	Both connection methods failed.<br>
	Possible link down between main capacitor and Shield Wall Controller.<br><br>
	<span style="color:red"><strong>Attempt manual response input above!</strong></span>
	"""
except socket.error:
	print "Content-type: text/html"
        print
	print """
	MainCap1 Toggle Challenge/Response Auth Request v.1.0<br>
        <br>
	Invalid IP address.
	<br>
	"""
