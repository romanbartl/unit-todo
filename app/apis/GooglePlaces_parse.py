#!/usr/bin/python3

import urllib.request
import sys
import xml.etree.ElementTree as ET

if (len(sys.argv) != 3) or sys.argv[1] != '--object':
    print("Usage: ./GooglePlaces_parse.py --object <object>", file=sys.stderr)
    exit()

obj = sys.argv[2]


rq = 'https://maps.googleapis.com/maps/api/place/textsearch/xml?query='+obj+'+in+Brno&key=AIzaSyDfotL66FGoibMafL-c8oNk8joyFtpcc6U'
print(rq)
file = urllib.request.urlopen(rq)
root = ET.fromstring(file.read())

if root[0].text != 'OK':
    print("Corrupted XML!", file=sys.stderr)
    exit()

l = []
for result in root[1:]:
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


for n in l:
    #print(n['name'] + ' [' + n['latitude'] + '; ' + n['longitude'] + ']: ', end='')
    #for k in n['tags']:
    #    print('#' + k, end='')
    #print("")
    insertion = "INSERT INTO `item` (`name`, `lati`, `longi`) VALUES ('"+n['name']+"','"+n['latitude']+"','"+n['longitude']+"');"
    print(insertion)

