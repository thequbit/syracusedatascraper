<?php
	require_once("_header.php");
?>

	<br>
	<h3>About This Site</h3>
	<br>
	
	<a href="http://www.syracuse.com/">Syracuse.com</a> has a section within it where data sets are presented.  These data sets are accessed through <a href="http://www.caspio.com/">Caspio.com</a>.  One of these data sets
	is crime data from 2011/1/1 to current day (actually a few days behind, but that's okay).  Access to that data set can be found <a href="http://www.syracuse.com/crime/police-reports/">here</a>.<br>
	<br>
	
	The tool seen on Syracuse.com will display up to 250 items at a time, and display those items on a map.  The current count of items in the database exceeds 50,000, and thus to consume all of the data would take
	a very long time (and a lot of clicking!).  This site attempts to provide a open and free RESTful Application Programming Interface (API) for getting the data in a useful and meaningful way.<br>
	<br>

	<br>
    <h3>Statistics</h3>
	<br>

	<div class="stats">
	
		<?php
		
			require_once("./tools/CrimesManager.class.php");
			
			$mgr = new CrimesManager();
			$stats = $mgr->getstats();
		
			// 'totalcrimes' => $row['totalcrimes'],'totaladdresses' => $row['totaladdresses'],'totaldays' => $row['totaldays'],'totalcrimetypes' => $row['totalcrimetypes']);
		
			echo "Total Crimes: <b>" . $stats->totalcrimes . "</b><br>";
			echo "Unique Addresses: <b>" . $stats->totaladdresses . "</b><br>";
			echo "Total Days: <b>" . $stats->totaldays . "</b><br>";
			echo "Crime Types: <b>" . $stats->totalcrimetypes . "</b><br>";
		
		?>
	
	</div>
	
	<br>
	The above numbers are generated via the data collected and placed into the database.   <b>Total Crimes</b> is the total number of reported crimes that exist within the Caspio.com database.  <b>Unique Addresses</b>
	is the number of unique addresses that are associated with the <b><?php echo $stats->totalcrimes; ?></b> crimes within the database.  Note that this number sits at an average of over 5 calls per address in less than
	a three year time frame.  <b>Total Days</b> is the total number of days worth of data that is in the database.  Data is available back to 1/1/2011.  <b>Crime Types</b> is the number of different types of crimes that
	are reported.  This includes Larceny, Arson, Burglary, etc.<br>
	<br>
	
	<br>
	<h3>Contact Information</h3>
	<br>
	Would you like more information about the site, would like to contact the maintainer, or suggest an addition/modification to the site?  You can do so at <a href="mailto:twofiftyfivelabs@gmail.com">twofiftyfivelabs@gmail.com</a>.<br>
	<br>
	This website is run by Tim Duffy of West Henrietta, NY.<br>
	<br>

<?php
	require_once("_footer.php");
?>