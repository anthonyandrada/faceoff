#!/usr/bin/python

# Import modules for CGI handling, Date/Time/IP, PostGres, URL handling
import cgi, cgitb, datetime, os, psycopg2, urllib, urllib2, requests
# Import modules for AES encryption
from Crypto.Cipher import AES
from aes import AESCipher

# CGI traceback
cgitb.enable(display=1, logdir='/cgi-logs', context=5, format='html')

# Create instance of FieldStorage 
form = cgi.FieldStorage()

username = form.getvalue('username')
# open encryption key file
with open('/usr/lib/cgi-bin/key/aes.key', 'r') as file:
    e = AESCipher(file.read())
try:
    with psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'") as conn:
        with conn.cursor() as curs:
            curs.execute("SELECT password FROM profile WHERE username = %s", (username,))
            result = curs.fetchone()
except:
    message = "<h1>Error: Username doesn't exist.</h1>"
else:
    if form.getvalue('password') == e.decrypt(result[0]):
        with psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'") as conn:
            with conn.cursor() as curs:
                curs.execute("SELECT first_name FROM profile WHERE username = %s", (username,))
                name = curs.fetchone()
        #payload = {'username': username}
        #headers = {'Content-type': 'application/x-www-form-urlencoded'}
        #r = requests.post("http://faceoff.ddns.net/upload.php", data=payload, headers=headers)
        message = '<h1>User %s logged in.</h1>' % (username)
        # fetch client IP address
        ip = os.environ["REMOTE_ADDR"]
        # read current datetime stamp, strip milliseconds
        last_login = str(datetime.datetime.now()).split('.')[0]
        with psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'") as conn:
            with conn.cursor() as curs:
                curs.execute("UPDATE profile SET last_login = %s, ip = %s WHERE username = %s", (last_login, ip, username))
        print "Location:http://faceoff.ddns.net/upload.php"
        print # to end the CGI response headers.      

    else:
        message = '<h1>Error: Incorrect password.</h1>'
        print "Content-type:text/html\r\n\r\n" # HTML code
        print '<html>'
        print '<head>'
        print '<title>Login</title>'
        print '</head>'
        print '<body>'
        print message
        print '</body>'
        print '</html>'
        



