<?php


function get_user($lineid){ // ดึงข้อมูลผู้ใช้งาน
	global $dbcon;
	$data 	= [$lineid];
	$sql 	= "SELECT * FROM api_line_user WHERE line_userid = ?";
	$stmt 	= $dbcon->prepare($sql);
	$stmt->execute($data);
	return $stmt;
}


function fetch_prefix() { //Function ดึงคำนำหน้า
	global $dbcon;
	$sql 	= "SELECT * FROM api_line_prefix";
	$stmt 	= $dbcon->prepare($sql);
    $stmt->execute();
	return $stmt;
}


function fetch_depart() { //Function ดึงแผนก
	global $dbcon;
	$sql 	= "SELECT * FROM api_line_depart";
	$stmt 	= $dbcon->prepare($sql);
    $stmt->execute();
	return $stmt;
}

function fetch_meeting_room(){ // Function ดึงห้องประชุม

	global $dbcon;
	$sql	= "SELECT * FROM api_line_room";
	$stmt	= $dbcon->prepare($sql);
	$stmt->execute();
	return $stmt;
}


function get_detail_booking_room($id){ //Function ดึงรายละเอียดผู้จองห้องประชุม

	global $dbcon;
	$sql = "SELECT GROUP_CONCAT(d.pre_name,b.fname,'  ',b.lname) as fullname,c.room_name FROM `api_line_bookingroom` a
			LEFT JOIN api_line_user b ON a.`id_user` = b.line_userid
			LEFT JOIN api_line_room c ON a.`id_room` = c.id
			LEFT JOIN api_line_prefix d ON d.id = b.prefix
			WHERE a.`id` = ".$id;

	$stmt	= $dbcon->prepare($sql);
	$stmt->execute();
	return $stmt;
}

function get_bookingroom($iduser){ //Function ดึงรายการจองห้องประชุมตามไอดี login
	global $dbcon;
	$data = [$iduser];
	$sql = "SELECT a.id,b.room_name,a.topic,a.start_date,a.end_date FROM `api_line_bookingroom` a
			LEFT JOIN api_line_room b ON a.`id_room` = b.id
			WHERE a.status_booking = 1 AND a.`id_user` = ? ORDER BY a.`start_date` DESC";
	$stmt	= $dbcon->prepare($sql);
	$stmt->execute($data);
	return $stmt;
}


function get_manager_meetingroom(){

	global $dbcon;
	$sql 	= "SELECT `id_linegroup` FROM `api_line_groupname` WHERE `manage_meeting` = 1";
	$stmt	= $dbcon->prepare($sql);
	$stmt->execute();
	return $stmt;

}



function linepushMessage($lineid,$messages) {  // Function Line PUSH Message
$chanelaccesstoken = 'xxxx'; 

$data = [
'to' => $lineid,
'messages' => $messages
];

$post = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.line.me/v2/bot/message/push");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$headers = array("Content-Type:application/json", "Authorization: Bearer $chanelaccesstoken", );
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
return $result;

}



// Function ส่ง Flexmessage
function lineflexmessage($iduser,$messages){
	$access_token = 'xxx';
$url = 'https://api.line.me/v2/bot/message/push';


$data = array('to' => $iduser ,'messages' => array($messages));
$post = json_encode($data);

$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);
return $result;
}


  /* Start Function convert datetime to day ago */
  function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'ปี',
        'm' => 'เดือน',
        'w' => 'สัปดาห์',
        'd' => 'วัน',
        'h' => 'ชม.',
        'i' => 'นาที',
        's' => 'วินาที',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ที่แล้ว' : 'just now';
}
/* End function Convert datetime to day ago */


/* Function Thai date */

function DateThai($strDate,$endDate)
  {
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $endDay = date("j",strtotime($endDate));
    $endHour = date("H",strtotime($endDate));
    $endMinute = date("i",strtotime($endDate));

    $strMonthThai=$strMonthCut[$strMonth];
    return "$strHour:$strMinute - $endHour:$endMinute $strDay $strMonthThai $strYear";
  }
  /* End Function */


function get_depart_edoc($userId){ //ดึงรหัสแผนกเพื่อไปใช้กับระบบลงรับหนัง
	global $dbcon;
	$data 	= [ $userId ];
	$sql 	= "SELECT id_dep_eoffice FROM `api_line_depart` a LEFT JOIN api_line_user b ON a.id = b.depart WHERE b.line_userid = ?";
	$stmt	= $dbcon->prepare($sql);
	$stmt->execute($data);
	$row 	= $stmt->fetch();
	$get 	= $row['id_dep_eoffice'];
	return $get;
}

?>