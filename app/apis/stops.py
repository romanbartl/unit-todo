#!/usr/bin/env python3

import sys
import math
#import datetime
from time import gmtime, strftime
import subprocess



def dist(lat1, lon1, lat2, lon2):
    a = math.sin((lat2-lat1)/2.)**2  +  math.cos(lat1) * math.cos(lat2) * math.sin((lon2-lon1)/2)**2
    c = 2*math.atan2(math.sqrt(a), math.sqrt(1-a))
    return 6378*c

class StopRepository:
    def __init__(self):

        self.l = []

        with open('stops.txt', 'r') as f:
            f.readline()
            for line in f:
                name = line.split('"')[1]
                lat = float(line.split('"')[2].split(",")[1])
                lon = float(line.split('"')[2].split(",")[2])
                self.l.append( (name, lat, lon) )
        



# read arguments
if (len(sys.argv) != 7) or (sys.argv[1] != '--from') or (sys.argv[4] != '--to'):
    print("Usage: ./NearestStop.py --from <lat> <lon> --to <lat> <lon>", file=sys.stderr)
    exit()

s_lat = float(sys.argv[2])
s_lon = float(sys.argv[3])
f_lat = float(sys.argv[5])
f_lon = float(sys.argv[6])

stops = StopRepository()
s_stop = ""
s_min = 15000
f_stop = ""
f_min = 15000
for s in stops.l:
    s_d = dist(s[1], s[2], s_lat, s_lon)
    f_d = dist(s[1], s[2], f_lat, f_lon)
    if s_d < s_min:
        s_min = s_d
        s_stop = s[0]
    if f_d < f_min:
        f_min = f_d
        f_stop = s[0]


d = strftime("%d.%m.%Y", gmtime())
t = strftime("%H:%M", gmtime())


print(s_stop)
print(f_stop)
print(d)
print(t)
