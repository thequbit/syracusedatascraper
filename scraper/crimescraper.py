import sys

from datetime import date, timedelta

import urllib
import urllib2
from bs4 import BeautifulSoup

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

    table = soup.findChildren('table')[0]
    rows = table.findChildren('tr')

    return rows

def pullcells(row):
    cells = row.findChildren('td')

    crime = cells[0].get_text().encode('ascii','ignore')
    address = cells[1].get_text().encode('ascii','ignore')
    city = cells[2].get_text().encode('ascii','ignore')
    department = cells[3].get_text().encode('ascii','ignore')
    crimedate = cells[4].get_text().encode('ascii','ignore')
    time = cells[5].get_text().encode('ascii','ignore')
    
    return (crime,address,city,department,crimedate,time)

def addcrime(crime,address,city,department,crimedate,time):
    return True

def main(argv):
    print '[SCRAPER] Application Started.'

    print '[INFO   ] Scraping Website ...'

    count = 0
    ignorecount = 0
    cdate = date(2011,1,1)
    while cdate < date.today() + timedelta(days=1):
        rows = getrows(cdate)
        print "[INFO   ] Parsing daterange {0} - {1}, with {2} rows ...".format(cdate,cdate+timedelta(days=30),len(rows))
        for row in rows:
            try:
                crime,address,city,department,crimedate,time = pullcells(row)
                addcrime(crime,address,city,department,crimedate,time)
                count += 1
            except:
                print '[WARNING] Bad row found, ignoring.'
                ignorecount += 1
        cdate = cdate + timedelta(days=1)

    print '[INFO   ] Processed {0} crimes successfully. ({1} ignored rows)'.format(count,ignorecount)

    print '[SCRAPER] Application Exiting.'

if __name__ == '__main__': sys.exit(main(sys.argv))
