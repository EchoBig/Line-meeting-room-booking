<?php
header("Content-type:application/json; charset=UTF-8");          
header("Cache-Control: no-store, no-cache, must-revalidate");         
header("Cache-Control: post-check=0, pre-check=0", false); 
	include_once '../inc/connection.php';
	
	// mysqli_set_charset($dbcon, "utf8");
	
	$sql1 = "SELECT * FROM api_line_room";
	

	$result1 = $dbcon->prepare($sql1);
	$result1->execute();
	$row = $result1->fetchAll();
	$numRow	 = $result1->rowcount();

	
	$resource = array();
		
	if ($numRow > 0) {
		
			foreach ($row as $key => $value) {
				 $resource[] = [ 'id' => $value['id'] , 'title' => $value['room_name']];
			}
	
	}

	

	// $value 	= null;
	// $row 	= null;
	// $numRow	= null;

	$sql2 = "SELECT * FROM api_line_bookingroom WHERE status_booking = 1";
	
	$result2 = $dbcon->prepare($sql2);
	$result2->execute();
	$row2 = $result2->fetchAll();
	$numRow2	 = $result2->rowcount();
	$events = array();

	if ($numRow2 > 0) {
		
		
		$i = 1;

		foreach ($row2 as $key => $value2) {
			
			$start = str_replace(' ','T',$value2['start_date']);

            $end = str_replace(' ','T',$value2['end_date']);

			$events[] = [
			   'id' => $value2['id'],
			   'resourceId' => $value2['id_room'],
			   'start' => $start,
			   'end' => $end,
			   'title' => $value2['topic'],
            ];
            
            $i++;
		}
	}
	
	
	if(isset($_GET['resource'])){
		echo json_encode($resource);
	}
	
	if(isset($_GET['events'])){
		echo json_encode($events);
	}
	
?>
