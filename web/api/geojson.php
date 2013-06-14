<?

	require_once("../tools/CrimesManager.class.php");
	$mgr = new CrimesManager();

	if( isset($_GET['year']) && isset($_GET['month']) )
	{
		$year = $_GET['year'];
		$month = $_GET['month'];
		
		$results = $mgr->getallbymonth($year,$month);
		
		$features = array();
		foreach($results as $result)
		{
		    $latlng = array();
			$latlng[] = $result->lng;
			$latlng[] = $result->lat;
			
			$feature = (object)array('type' => 'Feature', 
			                          'geometry' => (object)array('type' => 'Point', 'coordinates' => $latlng),
									  'properties' => (object)array('crime' => $result->crime, 'fulladdress' => $result->fulladdress, 'department' => $result->department, 'crimedate' => $result->crimedate, 'crimetime' => $result->crimetime)
									  );
			
			$features[] = $feature;
		}
		
		$retval = (object)array('type' => 'FeatureCollection', 'features' => $features);
		
		echo json_encode($retval);
	}
	else
	{
		$err = (object) array('errorcode' => 1, 'errortext' => 'Invalid Input.');
		echo json_encode($err);
	}

	exit;
	
?>