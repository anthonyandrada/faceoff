'''
Created on Mar 13, 2017

@author: Anthony Andrada
'''
import re

class stringScrub(object):

def scrub(string)
    string = re.sub("[$;<>\[\]'?\"/\\]", '', string)
    return string
