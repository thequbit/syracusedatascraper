<?

	require_once("DatabaseTool.class.php");

	class AddressesManager
	{
		function add($rawaddress,$fulladdress,$lat,$lng,$zipcode)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO addresses(rawaddress,fulladdress,lat,lng,zipcode) VALUES(?,?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssss", $rawaddress,$fulladdress,$lat,$lng,$zipcode);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('addressid' => $row['addressid'],'rawaddress' => $row['rawaddress'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($addressid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM addresses WHERE addressid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $addressid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('addressid' => $row['addressid'],'rawaddress' => $row['rawaddress'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode']);
	
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
				$query = 'SELECT * FROM addresses';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('addressid' => $row['addressid'],'rawaddress' => $row['rawaddress'],'fulladdress' => $row['fulladdress'],'lat' => $row['lat'],'lng' => $row['lng'],'zipcode' => $row['zipcode']);
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

		function del($addressid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM addresses WHERE addressid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $addressid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update($rawaddress,$fulladdress,$lat,$lng,$zipcode)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE addresses SET rawaddress = ?,fulladdress = ?,lat = ?,lng = ?,zipcode = ? WHERE addressid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssssss", $rawaddress,$fulladdress,$lat,$lng,$zipcode, $addressid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		///// Application Specific Functions

	}

?>
