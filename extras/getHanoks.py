# -*- coding: utf-8 -*-
"""
Created on Tue Dec 22 10:30:10 2015

@author: sven
"""

import mysql.connector as mySqlCon
from mysql.connector import Error
import os
import requests
from html.parser import HTMLParser
from bs4 import BeautifulSoup

#rootDir = '/home/sven/Desktop/WISS/Aufträge'
#for dirName, subdirList, fileList in os.walk(rootDir):
#    print('Found directory: %s' % dirName)
#    for fname in fileList:
#        print('\t%s' % fname)


db_name = 'wiss_db2'
""" Connect to MySQL database """
conn = mySqlCon.connect(host='localhost',
                               database=db_name,
                               user='sven',
                               password='sven')
if conn.is_connected():
    print('Connected to MySQL database')
else:
    print('no mysql connection, die')
    exit

cursor = conn.cursor();

#cursor.execute('delete from customer_order;');
#conn.commit();

#cursor.execute('DROP TABLE IF EXISTS directories;')
#cursor.execute('CREATE TABLE directories (id INT, directory varchar(30), id_parent INT, PRIMARY KEY(id));')
#conn.commit()
cursor.execute('SELECT modul_nr FROM ict_module WHERE hanoks="";')
modules = cursor.fetchall()
#

for module in modules:
    try:
        id_modul = module[0]
        print('ID Modul: '+str(id_modul))
        url="https://cf.ict-berufsbildung.ch/modules.php?name=Mbk&a=20101&cmodnr="+str(id_modul)+"&noheader=1"
        data = requests.get(url)
        
        soup=BeautifulSoup(data.content, 'html.parser')
        hanoks = str(soup.find_all('tab')[0]).replace("'",'"')
        
        t_query = "UPDATE ict_module SET hanoks ='"+str(hanoks)+"' WHERE modul_nr='"+str(id_modul)+"';"
        cursor.execute(t_query)
        conn.commit()

    except Exception as e:
        print('Failed to load modul hanoks: '+str(id_modul))
        print(e)
        print("Invalid character? try 'hanoks.decode(encoding='UTF-8',errors='strict')'")
        print('http://www.tutorialspoint.com/python/string_decode.htm')
        pass
    
conn.commit()
conn.close()



#if __name__ == '__main__':
#    createSales()