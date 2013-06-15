import MySQLdb as mdb
import _mysql as mysql
import re

class addresses:

    __settings = {}
    __con = False

    def __init__(self):
        configfile = "sqlcreds.txt"
        f = open(configfile)
        for line in f:
            # skip comment lines
            m = re.search('^\s*#', line)
            if m:
                continue

            # parse key=value lines
            m = re.search('^(\w+)\s*=\s*(\S.*)$', line)
            if m is None:
                continue

            self.__settings[m.group(1)] = m.group(2)
        f.close()

        # create connection
        self.__con = mdb.connect(host=self.__settings['host'], user=self.__settings['username'], passwd=self.__settings['password'], db=self.__settings['database'])

    def __sanitize(self,valuein):
        if type(valuein) == 'str':
            valueout = mysql.escape_string(valuein)
        else:
            valueout = valuein
        return valuein

    def add(self,rawaddress,fulladdress,lat,lng,zipcode):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO addresses(rawaddress,fulladdress,lat,lng,zipcode) VALUES(%s,%s,%s,%s,%s)",(self.__sanitize(rawaddress),self.__sanitize(fulladdress),self.__sanitize(lat),self.__sanitize(lng),self.__sanitize(zipcode)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,addressid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM addresses WHERE addressid = %s",(addressid))
            row = cur.fetchone()
            cur.close()
        return row

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM addresses")
            rows = cur.fetchall()
            cur.close()

        _addresses = []
        for row in rows:
            _addresses.append(row)

        return _addresses

    def delete(self,addressid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM addresses WHERE addressid = %s",(addressid))
            cur.close()

    def update(self,addressid,rawaddress,fulladdress,lat,lng,zipcode):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE addresses SET rawaddress = %s,fulladdress = %s,lat = %s,lng = %s,zipcode = %s WHERE addressid = %s",(self.__sanitize(rawaddress),self.__sanitize(fulladdress),self.__sanitize(lat),self.__sanitize(lng),self.__sanitize(zipcode),self.__sanitize(addressid)))
            cur.close()

##### Application Specific Functions #####

    def getbyrawaddress(self,rawaddress):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT fulladdress,lat,lng,zipcode FROM addresses WHERE rawaddress = %s",(rawaddress))
            rows = cur.fetchall()
            cur.close()
        if len(rows) != 0:
            valid = True
            fulladdress,lat,lng,zipcode = rows[0]
        else:
            valid = False
            fulladdress = ""
            lat = 0
            lng = 0
            zipcode = ""
        return valid,fulladdress,lat,lng,zipcode
    
