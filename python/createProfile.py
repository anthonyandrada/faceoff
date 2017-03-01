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
    
    def check_username(self):
        with psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'") as conn:
            with conn.cursor() as curs:
                curs.execute("SELECT COUNT(*) FROM profile WHERE username = %s", (self.username,))
                result = curs.fetchone() 
                if result[0] !=0:
                    return False
                else:
                    return True
    
    def register_user(self):
        with psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'") as conn:
            with conn.cursor() as curs:
                SQL = "INSERT INTO profile (username, password, first_name, last_name, last_login, ip) VALUES (%s, %s, %s, %s, %s, %s);"
                data = (self.username, self.password, self.first_name, self.last_name, self.last_login, self.ip)  
                curs.execute(SQL, data)

    
