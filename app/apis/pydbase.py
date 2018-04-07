#!/usr/bin/env python3

import MySQLdb as mysql

class DB:
    pass
    def __init__(self):
        # Open database connection
        self.db = mysql.connect(host="localhost", user="unit", passwd="unitbrno", db="unit")
        self.cursor = self.db.cursor()
    
    def execute(self, command):
        self.cursor.execute(command)
        return cursor.fetchall()

    def __exit__(self, exc_type, exc_value, traceback):
        # disconnect from server
        self.db.close()