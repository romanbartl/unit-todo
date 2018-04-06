#!/usr/bin/python3

#import MySQLdb as mysql
import re
import requests
from html.parser import HTMLParser

# Open database connection
#db = mysql.connect(host="213.136.88.33", user="unit", passwd="unitbrno", db="unit")

# prepare a cursor object using cursor() method
#cursor = db.cursor()

# execute SQL query using execute() method.
#cursor.execute("SELECT VERSION()")

# Fetch a single row using fetchone() method.
#data = cursor.fetchone()
#print "Database version : %s " % data

# disconnect from server
#db.close()


start = u'Tetčice'
end = u'Rozcestí'
date = u'6.4.18'
time = u'20:20'
urlrequest = 'https://www.idsjmk.cz/spojeni.aspx?f='+ start + '&t=' + end + '&date=' + date + '&time=' + time



result = requests.get(urlrequest)
result.encoding = 'utf-8'
html = result.text.replace("windows-1250", "utf-8")

table = ['time', 'date', 'stop', 'arr', 'dep', 'zone', 'link']
class MyHTMLParser(HTMLParser):
    def __init__(self):
        self.d = {}
        self.ld = []
        self.lld = []
        self.i = -1
        self.key = ''
        super().__init__()

    def handle_starttag(self, tag, attrs):
        if tag == 'tr':
            
            if ('class', 'datarow first') in attrs:
                self.ld = []
                self.d = {}
                self.i = 0

            if ('class', 'datarow') in attrs:
                self.d = {}
                self.i = 0
        
        elif (tag == 'td') and (self.i != -1):
            self.key = table[self.i]
            self.i += 1
        
        elif (tag == 'span'):
            pass



    def handle_endtag(self, tag):
        if self.i > 6:
            self.i = -1
        if (tag == 'td') and (self.d != {}):
            self.ld.append(self.d)
            self.d = {}

        if (tag == 'tr') and (self.ld != []):
            self.ld.append(self.d)
            self.lld.append(self.ld)
            self.d = {}
            self.ld = []

    def handle_data(self, data):
        if self.key != '':
            if (data == '\xa0') or (data.strip() == '') or (data == '>'):
                data = ''
            self.d[self.key] = data
            self.key = ''

parser = MyHTMLParser()
parser.feed(html)
print(parser.lld)



#parse = HTMLParser()
#print(parse.feed(html))




#print(html)