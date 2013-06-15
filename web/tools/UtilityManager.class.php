<?php

	class UtilityManager
	{
		function IsValidDate($date)
		{
		
			// code was taken from user Ravern (http://stackoverflow.com/users/179104/raveren) from this post:
			//
			//	http://stackoverflow.com/questions/597456/how-to-validate-a-mysql-date-in-php
			//
			
			return preg_match( '#^(?P<year>\d{2}|\d{4})([- /.])(?P<month>\d{1,2})\2(?P<day>\d{1,2})$#', $date, $matches )
				&& checkdate($matches['month'],$matches['day'],$matches['year']); 
		}
		
		function IsNumber($number)
		{
			
			// will return true if the passed in value is a number
			return is_numeric($number);
		
		}
	
	}

?>