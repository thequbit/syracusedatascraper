<?php
	require_once("_header.php");
?>

<?php

	//
	// Sanity Check Inputs
	//
	
	require_once("./tools/UtilityManager.class.php");
	
	$util = new UtilityManager();

	// get the posted data variable
	if( isset($_GET['date']) )
		$date = $_GET['date'];
	else
		$date = "2013-01-01";

	// see if we got a date passed in, or if we should be use todays date
	if( $date == "" )
	{
		$date = "2013-01-01";
	}
	
	// check for none-case ... we handle as the current date later in code
	if( $date != "" )
	{
	
		// check that the date is valid
		if( $util->IsValidDate($date) == 0 || $util->IsValidDate($date) == False )
		{
			// not a valid date

			echo '<script>';
			echo 'window.location = "./index.php"';
			echo '</script>';
		}
		else
		{
			// move on to rest of page, no need to do anything
		}
		
	}
	
?>

	<?php
	
		// calculate tomorrow
		$tomorrowtime = strtotime ('+1 day', strtotime($date)) ;
		$tommorrow = date('Y-m-d', $tomorrowtime);
		// calculate yesterday
		$yesterdaytime = strtotime ('-1 day', strtotime($date)) ;
		$yesterday = date('Y-m-d', $yesterdaytime);
	
		// display yesterday link
		echo '<div class="yesterdaylink">';
		echo '<a href="index.php?date=' . $yesterday . '">See Crimes for ' . date("l F j, Y",strtotime($yesterday)) . '</a>';
		echo '</div>';
		
		// if date not today, display tomorrow link
		if( $date != date("Y-m-d") )
		{
			echo '<div class="tomorrowlink">';
			echo '<a href="index.php?date=' . $tommorrow . '">See Crimes for ' . date("l F j, Y",strtotime($tommorrow)) . '</a>';
			echo '</div>';				
		}

	?>

	<br><br><br>
	<center><h1>Visual Crime Data for <?php echo date("l F j, Y",strtotime($date)); ?></h1></center><br>
	
	<div id="map" style="margin: auto; width: 800px; height: 400px;"></div>

	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript">

		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 10,
			center: new google.maps.LatLng(43.0481, -76.1478),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		
		var currentinfowindow = new google.maps.InfoWindow();

		loadData();
			
		function loadData()
		{
			var url = "./api/bydate.php?date=<?php echo $date; ?>";
			$.getJSON(url, function (response) {handleData(response)});
		}

		function handleData(response)
		{
			var n;
			for(n=0; n<response.features.length; n++)
			{
				lng = response.features[n].geometry.coordinates[0];
				lat = response.features[n].geometry.coordinates[1];
				crime = response.features[n].properties.crime;
				crimedate = response.features[n].properties.crimedate;
				crimetime = response.features[n].properties.crimetime;
				fulladdress = response.features[n].properties.fulladdress;
				department = response.features[n].properties.department;
				
				var myLatLng = new google.maps.LatLng(lat,lng);
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					title: crime,
					zIndex: 1
				});
				
				createpopup(marker,'<b>' + crime + '</b></br>Department: ' + department + '</br>Address: ' + fulladdress + '</br>Date: ' + crimedate + '</br>Time: ' + crimetime + '</br>Geo: ' + '[' + lat + ', ' + lng + ']');
			}   
		}

		function createpopup(marker, contentstring)
		{
			// add pop-up listener to marker
			google.maps.event.addListener(marker, 'click', function() {
				if( currentinfowindow )
				{
					currentinfowindow.close();
					currentinfowindow = new google.maps.InfoWindow({
						content: contentstring
					});
					currentinfowindow.open(map, marker);
				}
			});
		}

	</script>

<?php
	require_once("_footer.php");
?>