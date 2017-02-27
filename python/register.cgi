#!/usr/bin/python

# Import modules for CGI handling, timestamp 
import cgi, cgitb, datetime
# Import modules for AES encryption
from Crypto.Cipher import AES
from aes import AESCipher
# Import modules for getting IP
from urllib2 import urlopen
from PIL.ImageCms import createProfile

# CGI traceback
cgitb.enable(display=0, logdir='/cgi-logs', context=5, format='html')

# Create instance of FieldStorage 
form = cgi.FieldStorage() 

# Get name
first_name = form.getvalue('first_name', '')
last_name  = form.getvalue('last_name', '')
name = first_name + " " + last_name
# HTML code
print "Content-type:text/html\r\n\r\n"
print "<html>"
print "<head>"
print "<title>New User Registration - {name}</title>"
print "</head>"
print "<body>"

if 'username' not in form:
    print "<h1>Error!</h1>"
    print "Please enter a username."
    return
if 'password' not in form:
    print "<h1>Error!</h1>"
    print "Please enter a password."
    return

# Get data from field
username = form.getvalue('username')
password = psycopg2.Binary(AESCipher.encrypt(form.getvalue('password')))
last_login = str(datetime.datetime.now()).split('.')[0]
ip = urlopen('http://ip.42.pl/raw').read()

p = createProfile(username, password, first_name, last_name, last_login, ip)
p.connect()
if p.check_username() == True:
    print "Username available."
    if p.register_user() == True:
        print "User profile registered."
    else:
        print "Failed to register profile."
else:
    print "Error! Username already taken."
    return
p.disconnect()

#close HTML
print "</body>"
print "</html>"
