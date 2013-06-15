import sys

from datetime import date, timedelta
import time

import simplejson

import urllib
import urllib2
from bs4 import BeautifulSoup

from crimes import crimes
from addresses import addresses

# GROSS!
#aseurl = "http://b2.caspio.com/"
#tartdate = "01/01/2000".replace("/","%2F")
#nddate = "01/01/2014".replace("/","%2F")
#ostdata = "AppKey=183210004bc157ae61784b8da07a&ComparisonType1_1=MULTI_OR_%3D&MatchNull1_1=N&Value1_1=&ComparisonType5_1=MULTI_OR_%3D&MatchNull5_1=N&Value5_1=&ComparisonType2_1=LIKE&MatchNull2_1=N&Value2_1=&ComparisonType6_1=%3E%3D&MatchNull6_1=N&Value6_1={0}&ComparisonType3_1=%3D&MatchNull3_1=N&Value3_1=&ComparisonType6_2=%3C%3D&MatchNull6_2=N&Value6_2={1}&x=54&y=15&FieldName1=police_call_agency&Operator1=OR&NumCriteriaDetails1=1&FieldName2=police_call_address&Operator2=OR&NumCriteriaDetails2=1&FieldName3=police_call_city&Operator3=OR&NumCriteriaDetails3=1&FieldName4=HTML+Block+1&Operator4=&NumCriteriaDetails4=1&FieldName5=police_call_crime&Operator5=OR&NumCriteriaDetails5=1&FieldName6=police_call_date&Operator6=AND&NumCriteriaDetails6=2&FieldName7=HTML+Block+2&Operator7=&NumCriteriaDetails7=1&PageID=2&GlobalOperator=AND&NumCriteria=7&Search=1&PrevPageID=1".format(startdate,enddate)

def report(level,text):
    level = level.upper()
    print "[{0}] {1}".format(level,text)

def getrows(thedate):

    url = 'http://b2.caspio.com/dp.asp'

    d = str(thedate).split('-')
    startdate = "{0}/{1}/{2}".format(d[1],d[2],d[0])
    d = str(thedate + timedelta(days=1)).split('-')
    enddate = "{0}/{1}/{2}".format(d[1],d[2],d[0])

    postdata = "AppKey=183210004bc157ae61784b8da07a&ComparisonType1_1=MULTI_OR_%3D&MatchNull1_1=N&Value1_1=&ComparisonType5_1=MULTI_OR_%3D&MatchNull5_1=N&Value5_1=&ComparisonType2_1=LIKE&MatchNull2_1=N&Value2_1=&ComparisonType6_1=%3E%3D&MatchNull6_1=N&Value6_1={0}&ComparisonType3_1=%3D&MatchNull3_1=N&Value3_1=&ComparisonType6_2=%3C%3D&MatchNull6_2=N&Value6_2={1}&x=54&y=15&FieldName1=police_call_agency&Operator1=OR&NumCriteriaDetails1=1&FieldName2=police_call_address&Operator2=OR&NumCriteriaDetails2=1&FieldName3=police_call_city&Operator3=OR&NumCriteriaDetails3=1&FieldName4=HTML+Block+1&Operator4=&NumCriteriaDetails4=1&FieldName5=police_call_crime&Operator5=OR&NumCriteriaDetails5=1&FieldName6=police_call_date&Operator6=AND&NumCriteriaDetails6=2&FieldName7=HTML+Block+2&Operator7=&NumCriteriaDetails7=1&PageID=2&GlobalOperator=AND&NumCriteria=7&Search=1&PrevPageID=1".format(startdate,enddate)

    req = urllib2.Request(url, postdata)
    response = urllib2.urlopen(req)

    html = response.read()
    html = html.encode('utf-8').strip()
    soup = BeautifulSoup(html)

    table = soup.find('table', {'name': 'cbTable'} )
    rows = list()
    for row in table.findAll('tr'):
        rows.append(row)

    return rows

def pullcells(row):
    cells = row.findChildren('td')

    crime = cells[0].get_text().encode('ascii','ignore').strip()
    rawaddress = cells[1].get_text().encode('ascii','ignore').strip()
    city = cells[2].get_text().encode('ascii','ignore').strip()
    department = cells[3].get_text().encode('ascii','ignore').strip()
    crimedate = cells[4].get_text().encode('ascii','ignore').strip()
    crimetime = cells[5].get_text().encode('ascii','ignore').strip()
    valid = True    

    if crime == "Crime":
        valid = False

    return (valid,crime,rawaddress,city,department,crimedate,crimetime)

#
# code via Ralph Bean (github.com/ralphbean) from:
#   https://github.com/ralphbean/monroe/blob/master/wsgi/tg2app/tg2app/scrapers/propertyinfo.py
#
#def geocode(address):
    # TODO -- a more open way of doing this.
    # Here we have to sleep 1 second to make sure google doesn't scold us.
#    time.sleep(.25)
#    vals = {'address': address, 'sensor': 'false'}
#    qstr = urllib.urlencode(vals)
#    reqstr = "http://maps.google.com/maps/api/geocode/json?%s" % qstr
#    return simplejson.loads(urllib.urlopen(reqstr).read())

def geocodemq(address):
    key = "YOUR_KEY_HERE"
    vals = {'location': address}
    qstr = urllib.urlencode(vals)
    #print "QSTR = '{0}'".format(qstr)
    reqstr = "http://www.mapquestapi.com/geocoding/v1/address?key={0}&outFormat=json&maxResults=1&{1}".format(key,qstr)
    #print "Sending: {0}".format(reqstr)
    _json = simplejson.loads(urllib.urlopen(reqstr).read())
    return _json

def pulldata(_json):
    #print _json['results'][0]
    #treet = _json['results'][0]['locations'][0]['street']
    town = _json['results'][0]['locations'][0]['adminArea5']
    state = _json['results'][0]['locations'][0]['adminArea3']
    zipcode = _json['results'][0]['locations'][0]['postalCode'].split('-')[0]
    fulladdress = ", {0}, {1}, {2}".format(town,state,zipcode)
    lat = _json['results'][0]['locations'][0]['latLng']['lat']
    lng = _json['results'][0]['locations'][0]['latLng']['lng']
    return fulladdress,lat,lng,zipcode

def addcrime(crime,rawaddress,city,department,crimedate,crimetime):
    c = crimes()
    a = addresses()
 
    _crimetime = crimetime.split('-')[0].split(' ')[0]
    _ampm = crimetime.split('-')[0].split(' ')[1]
    if _ampm == "p.m.":
        _crimetime = "{0}:{1}:00".format(int(_crimetime.split(':')[0])+12,_crimetime.split(':')[1])    

    d = crimedate.split('/')
    _crimedate = "{0}-{1}-{2}".format(d[2],d[0],d[1])

    fulladdress = ""
    lat = 0
    lng = 0
    zipcode = ""

    if True:
    #if c.checkexists(crime,rawaddress,city,department,_crimedate,_crimetime) == False:

        valid,fulladdress,lat,lng,zipcode = a.getbyrawaddress(rawaddress)
        if valid == False:
            _address = "{0}, {1}, NY".format(rawaddress,city)
            _json = geocodemq(_address)
            _fulladdress,lat,lng,zipcode = pulldata(_json)
            fulladdress = "{0}{1}".format(rawaddress,_fulladdress)
        #if(crimedate.split('/')[2] == "2013"): 
           #json = geocode("{0},{1}, NY".format(rawaddress,city))
            #f _json['status'] != 'OK':
            #   raise Exception("Google API says 'NO MORE!'")
            #ulladdress = _json['results'][0]['formatted_address']
            #at = _json['results'][0]['geometry']['location']['lat']
            #ng = _json['results'][0]['geometry']['location']['lng']
            #ipcode = ""
            #or comp in _json['results'][0]['address_components']:
            #   if comp['types'][0] == "postal_code":
            #       zipcode = comp['long_name']
            #       break

        print "adding: {0},{1},{2},{3},{4},{5},{6},{7},{8},{9}".format(crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,_crimedate,_crimetime)

        c.add(crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,_crimedate,_crimetime)
        a.add(rawaddress,fulladdress,lat,lng,zipcode)
    else:
        print "crime already saved, ignoring ({0},{1},{2},{3},{4},{5},{6},{7},{8},{9})".format(crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,_crimedate,_crimetime)
        raise Exception("Debug, Stop.")

def main(argv):
    print '[SCRAPER] Application Started.'

    print '[INFO   ] Scraping Website ...'

    count = 0
    ignorecount = 0
    cdate = date(2011,1,1)
    while cdate < date.today() + timedelta(days=1):
        if cdate > date(2011,12,31):
            break;
        else:
            rows = getrows(cdate)
            print "[INFO   ] Parsing {0}, with {1} rows ...".format(cdate,len(rows))
            for row in rows:
                valid,crime,rawaddress,city,department,crimedate,crimetime = pullcells(row)
                if valid == True:
                    addcrime(crime,rawaddress,city,department,crimedate,crimetime)
                    count += 1
            cdate = cdate + timedelta(days=1)

    print '[INFO   ] Processed {0} crimes successfully. ({1} ignored rows)'.format(count,ignorecount)

    print '[SCRAPER] Application Exiting.'

if __name__ == '__main__': sys.exit(main(sys.argv))
