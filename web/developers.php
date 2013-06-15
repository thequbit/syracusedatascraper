<?php
	require_once("_header.php");
?>

	<br>
	<h3>Welcome Developers!</h3>
	<br>

	The main purpose of this site is to provide a method of easily getting to the troves of data that are within the database on Caspio.com (see the <a href="about.php">about</a> page for more on that).
	The site does have some cool interactive features on the <a href="maps.php">maps</a> page, however I am no UI designer (if that wasn't already apparent ...).<br>
	<br>
	You can gain access to all of the data within the database via RESTful API's.  Here is a list of the API's available, and examples of how to use them.  All API calls return <a href="http://www.geojson.org/geojson-spec.html">GEOJson</a>.
	<br>
	
	<br>
	<h4>By Month API</h4>
	<br>

	This API allows you to get all of the crime data for a single <b>month</b>.  You must pass in the year, and the month.  Here is an example:<br>
	<br>
	<div class="tab2">
		<a href="http://mycodespace.net/projects/cusedata/api/bymonth.php?year=2013&month=2">http://mycodespace.net/projects/cusedata/api/bymonth.php?year=2013&month=2</a><br>
	</div>
	<br>
	
	This API allows you to get all of the crime data for a single <b>day</b>.  You must pass in the date in ISO format (Y-m-d).  Here is an example:<br>
	<br>
	<div class="tab2">
		<a href="http://mycodespace.net/projects/cusedata/api/bydate.php?date=2013-1-1">http://mycodespace.net/projects/cusedata/api/bydate.php?date=2013-1-1</a><br>
	</div>
	<br>
	
	Here is a single month (2013-1-1) displayed on a Google Maps interface:<br>
	<br>
	<div class="tab2">
		<a href="http://mycodespace.net/projects/cusedata/geotest.php">Example Map for February 2013</a>
	</div>
	<br>
	
	If you have any questions please contact me with the contact information found on the <a href="about.php">about</a> page.<br>
	<br>
	
	You can find the full source code to this website, database structure, and web scrapers on github <a href="https://github.com/thequbit/syracusedatascraper">here</a>.<br>
	<br>
	
	<br>
	<h2>Happy Coding!</h2>
	<br>
	
<?php
	require_once("_footer.php");
?>