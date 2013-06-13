<?

	require_once("DatabaseTool.class.php");

	$from = $_GET["from"];

	switch($from)
	{
		default:
			echo "{}";
			break;

		case "crimes":
			require_once("CrimesManager.class.php");
			$mgr = new CrimesManager();
			echo json_encode($mgr->getall());
			break;

		case "addresses":
			require_once("AddressesManager.class.php");
			$mgr = new AddressesManager();
			echo json_encode($mgr->getall());
			break;

	}

?>