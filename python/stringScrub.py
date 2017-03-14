'''
Created on Mar 13, 2017

@author: Anthony Andrada
'''
import re

def scrub(string)
    string = re.sub("[$;<>'?\"/\\]", '', string)
    return string