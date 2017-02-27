'''
Created on Feb 27, 2017

@author: Anthony Andrada
'''
# import PostgreSQL driver module
import psycopg2

class createProfile(object):
    
    def __init__(self, username, password, first_name, last_name, last_login, ip):
        self.username = username
        self.password = password
        self.first_name = first_name
        self.last_name = last_name
        self.last_login = last_login
        self.ip = ip 
    
    def connect(self):
        global conn
        conn = psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'")
    
    def check_username(self):
        # check if username is taken, not finished
        curs.execute("SELECT * FROM profile")
        result = curs.fetchone()
        if result != 0:
            cur.close()
            return False
        else:
            return True
    
    def register_user(self):
        curs = conn.cursor()
        SQL = "INSERT INTO profile (username, password, first_name, last_name, last_login, ip) VALUES (%s, %s, %s, %s, %s, %s);"
        data = (self.username, self.password, self.first_name, self.last_name, self.last_login, self.ip)  
        curs.execute(SQL, data)
        conn.commit()

    def disconnect(self):
        conn.close()
    
