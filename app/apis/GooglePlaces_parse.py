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
    print(rq)
    file = urllib.request.urlopen(rq)

    print(file.read())
    break

    root = ET.fromstring(file.read())

    # check
    if root[0].text != 'OK':
        print("Corrupted XML: " + root[0].text, file=sys.stderr)
        exit()


    # read data
    l = []
    for result in root[1:-1]:
        d = {}
        d['tags'] = set()

        for param in result:
            if param.tag == 'name':
               d['name'] = param.text.replace('\'', '\\\'')
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
    
        insertion = "INSERT INTO `item` (`name`, `description`, `lati`, `longi`) VALUES ('"+n['name']+"','',"+n['latitude']+","+n['longitude']+")"
        db.execute(insertion)
        print(insertion)
        genItemID = "SELECT id FROM item WHERE lati="+n['latitude']+' AND longi='+n['longitude']
        itemID = db.select(genItemID)

        for t in d['tags']:
            genTagID = "SELECT `id` FROM `tag` WHERE `name`='"+t+"';"
            tagID = db.select(genTagID)
            if len(tagID) == 0:
                genTag = "INSERT INTO `tag` (`name`) VALUES ('"+t+"');"
                print(genTag)
                tagID = db.select(genTagID)
                db.execute(genTag)
            if (len(itemID) > 0) and (len(tagID) > 0): 
                genTagAssignment = "INSERT INTO `item_tag` (`item_id`, `tag_id`) VALUES ("+str(itemID[0])+","+str(tagID[0])+");"
                print(genTagAssignment)
                db.execute(genTagAssignment)

    #break
    print(root[-1].tag)
    rq = r'https://maps.googleapis.com/maps/api/place/textsearch/xml?pagetoken='+root[-1].text+r'&key=AIzaSyDfotL66FGoibMafL-c8oNk8joyFtpcc6U'
    #print(rq)

#print(i)

