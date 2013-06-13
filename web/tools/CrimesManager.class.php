<?

	require_once("DatabaseTool.class.php");

	class CrimesManager
	{
		function add($crime,$rawaddress,$fulladdress,$lat,$lng,$zipcode,$city,$department,$crimedate,$crimetime)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO crimes(crime,rawaddress,fulladdress,lat,lng,zipcode,city,department,crimedate,crimetime) VALUES(?,?,?,?,?,?,?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssssssssss", $crime,$rawaddress,$fulladdress,$lat,$lng,$zipcode,$city,$department,$crimedate,$crimetime);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('crimeid' => $row['crimeid'],'crime' => $row['crime'],'rawaddress' => $row['rawaddress'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode'],'city' => $row['city'],'department' => $row['department'],'crimedate' => $row['crimedate'],'crimetime' => $row['crimetime']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($crimeid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM crimes WHERE crimeid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $crimeid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('crimeid' => $row['crimeid'],'crime' => $row['crime'],'rawaddress' => $row['rawaddress'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode'],'city' => $row['city'],'department' => $row['department'],'crimedate' => $row['crimedate'],'crimetime' => $row['crimetime']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function getall()
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM crimes';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('crimeid' => $row['crimeid'],'crime' => $row['crime'],'rawaddress' => $row['rawaddress'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode'],'city' => $row['city'],'department' => $row['department'],'crimedate' => $row['crimedate'],'crimetime' => $row['crimetime']);
					$retArray[] = $object;
				}
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retArray;
		}

		function del($crimeid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM crimes WHERE crimeid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $crimeid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update($crime,$rawaddress,$fulladdress,$lat,$lng,$zipcode,$city,$department,$crimedate,$crimetime)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE crimes SET crime = ?,rawaddress = ?,fulladdress = ?,lat = ?,lng = ?,zipcode = ?,city = ?,department = ?,crimedate = ?,crimetime = ? WHERE crimeid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssssssssss", $crime,$rawaddress,$fulladdress,$lat,$lng,$zipcode,$city,$department,$crimedate,$crimetime, $crimeid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		///// Application Specific Functions

		function getallbymonth($year,$month)
		{
			try
			{
				$startdate = $year . "-" . $month . "-1";
				if( $month == 12 )
					$stopdate = $year + 1 . "-1-1";
				else
					$stopdate = $year . "-" . $month + 1 . "-1";
			
				$db = new DatabaseTool(); 
				$query = 'SELECT crime,fulladdress,lat,lng,zipcode,city,department,crimedate,crimetime FROM crimes WHERE crimedate >= ? AND crimedate <= ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss", $startdate,$stopdate);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('crime' => $row['crime'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode'],'city' => $row['city'],'department' => $row['department'],'crimedate' => $row['crimedate'],'crimetime' => $row['crimetime']);
					$retArray[] = $object;
				}
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retArray;
		}

	}

?>
