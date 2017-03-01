#!/usr/bin/python

# Import modules for CGI handling, timestamp 
import cgi, cgitb, datetime, os
from createProfile import createProfile
# Import modules for AES encryption
from Crypto.Cipher import AES
from aes import AESCipher
# Import modules for getting IP
from urllib2 import urlopen

# CGI traceback
cgitb.enable(display=1, logdir='/cgi-logs', context=5, format='html')

# Create instance of FieldStorage 
form = cgi.FieldStorage() 

# Get name
first_name = form.getvalue('first_name', '')
last_name  = form.getvalue('last_name', '')
name = first_name + " " + last_name
# Get data from field
username = form.getvalue('username')
with open('/usr/lib/cgi-bin/key/aes.key', 'r') as file:
    e = AESCipher(file.read())
password = e.encrypt(form.getvalue('password'))
last_login = str(datetime.datetime.now()).split('.')[0]
ip = os.environ["REMOTE_ADDR"]
p = createProfile(username, password, first_name, last_name, last_login, ip)
p.connect()
p.register_user()
p.disconnect()
# HTML code
print "Content-type:text/html\r\n\r\n"
print '<html>'
print '<head>'
print '<title>New User Registration - %s</title>' % name
print '</head>'
print '<body>'
print '<h1>Registered User: </br></h1>'
print '<h2>Username: %s </br>Password: %s </br>First Name: %s </br>Last Name: %s </br>Last Login: %s </br>IP: %s</h2>' % (username, password, first_name, last_name, last_login, ip)
print '</body>'
print '</html>'

