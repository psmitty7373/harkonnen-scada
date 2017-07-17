#!/usr/bin/env python
import socket
import cgi, cgitb

form = cgi.FieldStorage()

print "Content-type: text/html"
print

print """
Shield-wall Toggle Challenge/Response Auth Request v.1.0<br>
<br>
Initiating connection to: 10.10.10.175:881<br>
Handshake failed<br>
<strong>Connection failed</strong><br>
<strong>Error! Timeout</strong><br>
Possible link down between Shield-wall and Shield Wall Controller.<br><br>
<span style="color:red"><strong>MANUAL OVERRIDE REQUIRED!</strong></span>
"""
