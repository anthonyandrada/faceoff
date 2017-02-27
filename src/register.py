#!/usr/bin/python
# Import modules for CGI handling 
import cgi, cgitb
from Crypto.Cipher import AES
from aes import AESCipher
cgitb.enable(display=0, logdir='/cgi-logs', context=5, format='hmtl')
# Create instance of FieldStorage 
form = cgi.FieldStorage() 
# Get data from fields
if 'username' not in form:
    print "<H1>Error</H1>"
    print "Please enter a username."
    return
if 'password' not in form:
    print "<H1>Error</H1>"
    print "Please enter a password."
    return
username = form.getvalue('username')

first_name = form.getvalue('first_name', '')
last_name  = form.getvalue('last_name', '')
password = AESCipher.encrypt(form.getvalue('password'))
print "Content-type:text/html\r\n\r\n"
print "<html>"
print "<head>"
print "<title>Registration</title>"
print "</head>"
print "<body>"
print "<h2>Hello %s %s</h2>" % (first_name, last_name)
print "</body>"
print "</html>"
