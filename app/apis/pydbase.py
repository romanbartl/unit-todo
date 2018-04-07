#!/usr/bin/env python3

import MySQLdb as mysql

class DB:
    pass
    def __init__(self):
        # Open database connection
        self.db = mysql.connect(host="localhost", user="unit", passwd="unitbrno", db="unit")
        self.cursor = self.db.cursor()
    
    def execute(self, command):
        try:
                self.cursor.execute(command)
                self.db.commit()
        except Exception as e:
                print("Error: " + str(e))
                self.db.rollback()
        else:
                print('Success!')
                result = self.cursor.fetchall()
                return result

    def select(self, command):
        self.cursor.execute(command)
        
        row = self.cursor.fetchone()
        l = []
        while row is not None:
            l.append(row[0])
            row = self.cursor.fetchone()
        return l


    def __exit__(self, exc_type, exc_value, traceback):
        # disconnect from server
        self.db.close()