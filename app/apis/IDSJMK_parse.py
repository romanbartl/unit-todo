#!/usr/bin/python3

#import MySQLdb as mysql
import re
import requests
from html.parser import HTMLParser
import sys

# Open database connection
#db = mysql.connect(host="localhost", user="unit", passwd="unitbrno", db="unit")

# prepare a cursor object using cursor() method
#cursor = db.cursor()

# execute SQL query using execute() method.
#cursor.execute("SELECT VERSION()")

# Fetch a single row using fetchone() method.
#data = cursor.fetchone()
#print "Database version : %s " % data

start = u''
end = u''
date = u''
time = u''
key = ''
for n in range(1,len(sys.argv)-1, 2):
    if sys.argv[n] == '--from':
        start = sys.argv[n+1]
    elif sys.argv[n] == '--to':
        end = sys.argv[n+1]
    elif sys.argv[n] == '--date':
        date = sys.argv[n+1]
    elif sys.argv[n] == '--time':
        time = sys.argv[n+1]
    else:
        print("Usage: ./IDSJMK_parse.py --from <stop> --to <stop> --date <date dd.mm.yy> --time <time hh:mm>.", file=sys.stderr)
        exit()
    

#print(start + ' -> ' + end + ' @ ' + date + ', ' + time)
urlrequest = 'https://www.idsjmk.cz/spojeni.aspx?f='+ start + '&t=' + end + '&date=' + date + '&time=' + time

#print(urlrequest)


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
        self.link = False
        super().__init__()

    def handle_starttag(self, tag, attrs):
        if tag == 'tr':
            
            if ('class', 'datarow first') in attrs:
                if self.ld != []:
                    self.lld.append(self.ld)
                self.ld = []
                self.d = {}
                self.i = 0
                self.key = ''

            if ('class', 'datarow') in attrs:
                if self.d != {}:
                    self.ld.append(self.d)
                self.d = {}
                self.i = 0
        
        elif (tag == 'td') and (self.i != -1):
            self.link = False
            self.key = table[self.i]
            self.i += 1
        
        elif (tag == 'span'):
            pass



    def handle_endtag(self, tag):
        if (tag == 'td') and (self.i == 6):
            self.ld.append(self.d)

        if (tag == 'tr') and (self.ld != []):
            if (len(self.lld) == 2):
                self.lld.append(self.ld)
            self.d = {}
            self.i = -1
        
        if self.i > 6:
            self.i = -1

    def handle_data(self, data):
        if self.key != '':
            if self.key == 'link':
                if not self.link:
                    self.link = True
                    return
            if (data == '\xa0') or (data.strip() == '') or (data == '>'):
                data = ''
            self.d[self.key] = data
            self.key = ''

parser = MyHTMLParser()
parser.feed(html)

lld = []
for route in parser.lld:
    ld = []
    d = {}
    for it in route:

        if (it['arr'] != ''):
            d['endtime'] = it['arr']
            d['end'] = it['stop']
            d['endzone'] = it['zone']
            ld.append(d)
            d = {}

        d['starttime'] = it['dep']
        d['start'] = it['stop']
        d['startzone'] = it['zone']
        d['name'] = it['link']
    lld.append(ld)


for n in lld:
    for d in n:
                                                   # MHD id # init ID    # name          # start          # starttime         # end          # endtime
        insertion = "INSERT INTO Route VALUES ("+str(1)+","+str(1)+",'"+d['name']+"','"+d['start']+"','"+d['starttime']+"','"+d['end']+"','"+d['endtime']+"');"
        print(insertion)
        #cursor.execute(insertion)
        #print(insertion, end="\n")
#cursor.execute("INSERT INTO Route VALUES (")

# disconnect from server
#db.close()

