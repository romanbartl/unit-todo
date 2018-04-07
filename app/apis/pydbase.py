#!/usr/bin/env python3

import MySQLdb as mysql

class DB:
    pass
    def __init__(self):
        # Open database connection
        self.db = mysql.connect(host="localhost", user="unit", passwd="unitbrno", db="unit")
        self.db.set_character_set('utf8')
        self.cursor = self.db.cursor()
        self.cursor.execute('SET NAMES utf8;')
        self.cursor.execute('SET CHARACTER SET utf8;')
        self.cursor.execute('SET character_set_connection=utf8;')
    
    def execute(self, command):
        try:
                self.cursor.execute(command)
                self.db.commit()
        except Exception as e:
                self.db.rollback()
        else:
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