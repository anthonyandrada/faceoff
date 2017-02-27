'''
Created on Feb 27, 2017

@author: Anthony Andrada
'''
# import PostgreSQL driver module
import psycopg2

class createProfile:
    
    def __init__(self, username, password, first_name, last_name, last_login, ip):
        self.username = username
        self.password = password
        self.first_name = first_name
        self.last_name = last_name
        self.last_login = last_login
        self.ip = ip 
    
    def connect(self):
        conn = psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'")
    
    def check_username(self):
        # check if username is taken
        with conn:
            with conn.cursor() as cur:
                cur.execute('SELECT COUNT(*) FROM profile WHERE username = %s', username)      
        result = cur.fetchone()
        if result != 0:
            cur.close()
            return False
        else:
            return True
    
    def register_user(self):
        conn = psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'")
        cur = conn.cursor()
        cur.execute('INSERT INTO profile VALUES (username, password, first_name, last_name, last_login, ip) (%s, %s, %s, %s, %s, %s);', (username, password, first_name, last_name, last_login, ip))    
    
    def disconnect(self):
        conn.close()
    
