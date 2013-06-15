 <html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
  <title>Crime Data for Onondaga County, NY</title> 
  <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
</head> 
<body>

  <center>
	  <h1>Crime Data for Onondaga County, NY</h1>
	  <h3>February 1st, 2013  -  February 28th, 2013</h3>
	  <p style="font: 8px">please wait while the data loads on the map</p>
  </center>
  
  <div id="map" style="margin: auto; width: 800px; height: 600px;"></div>

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
			var url = "./api/geojson.php?year=2013&month=1";
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
					title: "[" + crimedate + "] " + crime,
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
</body>
</html>