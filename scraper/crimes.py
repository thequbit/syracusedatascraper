import MySQLdb as mdb
import _mysql as mysql
import re

class crimes:

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

    def add(self,crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,crimedate,crimetime):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO crimes(crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,crimedate,crimetime) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",(self.__sanitize(crime),self.__sanitize(rawaddress),self.__sanitize(fulladdress),self.__sanitize(lat),self.__sanitize(lng),self.__sanitize(zipcode),self.__sanitize(city),self.__sanitize(department),self.__sanitize(crimedate),self.__sanitize(crimetime)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,crimeid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM crimes WHERE crimeid = %s",(crimeid))
            row = cur.fetchone()
            cur.close()
        return row

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM crimes")
            rows = cur.fetchall()
            cur.close()

        _crimes = []
        for row in rows:
            _crimes.append(row)

        return _crimes

    def delete(self,crimeid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM crimes WHERE crimeid = %s",(crimeid))
            cur.close()

    def update(self,crimeid,crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,crimedate,crimetime):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE crimes SET crime = %s,rawaddress = %s,fulladdress = %s,lat = %s,lng = %s,zipcode = %s,city = %s,department = %s,crimedate = %s,crimetime = %s WHERE crimeid = %s",(self.__sanitize(crime),self.__sanitize(rawaddress),self.__sanitize(fulladdress),self.__sanitize(lat),self.__sanitize(lng),self.__sanitize(zipcode),self.__sanitize(city),self.__sanitize(department),self.__sanitize(crimedate),self.__sanitize(crimetime),self.__sanitize(crimeid)))
            cur.close()

##### Application Specific Functions #####

    def checkexists(self,crime,rawaddress,city,department,crimedate,crimetime):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT count(*) as count FROM crimes WHERE crime = %s and rawaddress = %s and city = %s and department = %s and crimedate = %s and crimetime = %s",(crime,rawaddress,city,department,crimedate,crimetime))
            row = cur.fetchone()
            cur.close()
        count, = row
        return bool(count)

