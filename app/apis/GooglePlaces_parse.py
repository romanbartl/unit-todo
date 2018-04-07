#!/usr/bin/python3

import urllib.request
import sys
import xml.etree.ElementTree as ET

# database
import pydbase as PyDBase
db = PyDBase.DB()


# arguments
if (len(sys.argv) != 3) or sys.argv[1] != '--object':
    print("Usage: ./GooglePlaces_parse.py --object <object>", file=sys.stderr)
    exit()
obj = sys.argv[2]



# get information from api
rq = 'https://maps.googleapis.com/maps/api/place/textsearch/xml?query='+obj+'+in+Brno&key=AIzaSyDfotL66FGoibMafL-c8oNk8joyFtpcc6U'
i = 0
while True:
    #print(rq)
    file = urllib.request.urlopen(rq)
    root = ET.fromstring(file.read())
    # check
    if root[0].text != 'OK':
        print("Corrupted XML!", file=sys.stderr)
        exit()


    # read data
    l = []
    for result in root[1:-1]:
        d = {}
        d['tags'] = set()

        for param in result:
            if param.tag == 'name':
               d['name'] = param.text
            elif param.tag == 'type':
                d['tags'].add(param.text)
            elif param.tag == 'geometry':
                for coord in param[0]:
                    if coord.tag == 'lat':
                        d['latitude'] = coord.text
                    else:
                        d['longitude'] = coord.text
            elif param.tag == 'id':
                d['id'] = param.text
        l.append(d)
        i+=1


    # generate SQL
    for n in l:
        #print(n)
    
        insertion = "INSERT INTO `item` (`name`, `description`, `lati`, `longi`) VALUES ('"+n['name']+"','',"+n['latitude']+","+n['longitude']+");"
        db.execute(insertion)
    break
    #rq = 'https://maps.googleapis.com/maps/api/place/textsearch/xml?pagetoken='+root[-1].text+'&key=AIzaSyDfotL66FGoibMafL-c8oNk8joyFtpcc6U'

#print(i)

