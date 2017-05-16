#!/usr/bin/python

# Import modules for CGI handling, timestamp 
import cgi, cgitb, datetime, os
from createProfile import createProfile
# Import modules for AES encryption
#from Crypto.Cipher import AES
#from aes import AESCipher
# Import modules for getting IP
from urllib2 import urlopen

# CGI traceback
cgitb.enable(display=1, logdir='/cgi-logs', context=5, format='html')

# Create instance of FieldStorage 
form = cgi.FieldStorage() 

# Get name from form data
first_name = form.getvalue('first_name', '')
last_name  = form.getvalue('last_name', '')
name = first_name + " " + last_name
# Get username from form data
username = form.getvalue('username')
# open encryption key file
#with open('/usr/lib/cgi-bin/key/aes.key', 'r') as file:
 #   e = AESCipher(file.read())
# pass password from form data to encryption function
#password = e.encrypt(form.getvalue('password'))
password = form.getvalue('password')
# read current datetime stamp, strip milliseconds
last_login = str(datetime.datetime.now()).split('.')[0]
# fetch client IP address
ip = os.environ["REMOTE_ADDR"]

# PostgreSQL function to write profile to database
p = createProfile(username, password, first_name, last_name, last_login, ip)
if p.check_username():
    p.register_user()
    message = '<h1>Registered User: </br></h1><h2>Username: %s </br>Password: %s </br>First Name: %s </br>Last Name: %s </br>Last Login: %s </br>IP: %s</h2>' % (username, password, first_name, last_name, last_login, ip)
else:
    message = '<h1>Error: Username in use.</h1>'

print("Location:http://faceoff.ddns.net/")
print # to end the CGI response headers.
# HTML code
print "Content-type:text/html\r\n\r\n"
print '<html>'
print '<head>'
print '<title>New User Registration - %s</title>' % name
print '</head>'
print '<body>'
print message
print '<h2><a href="http://faceoff.ddns.net/index.html">Login</a></h2>'
print '</body>'
print '</html>'

