<?

	require_once("../tools/CrimesManager.class.php");
	require_once("../tools/UtilityManager.class.php");
	
	$mgr = new CrimesManager();

	if( isset($_GET['date']) )
	{
		$date = $_GET['date'];

		$util = new UtilityManager();
        if( !($util->IsValidDate($date) == 0 || $util->IsValidDate($date) == False) )
		{
			$results = $mgr->getallbydate($date);
		
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
			$err = (object) array('errorcode' => 2, 'errortext' => 'Invalid Date.');
			echo json_encode($err);
		}
	}
	else
	{
		$err = (object) array('errorcode' => 1, 'errortext' => 'Must Supply Date.');
		echo json_encode($err);
	}

	exit;
	
?>