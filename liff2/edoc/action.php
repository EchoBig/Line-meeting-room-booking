<?php
header('Content-Type: application/json');
include_once '../inc/connection.php';
include_once '../inc/function.php';

$iduser = $_POST['iduser'];
$action = $_POST['action'];
$page 	= $_POST['p'];
$search = $_POST['s'];

if ($action == 'checkuser') {
  $stmt   = get_user($iduser); //เรียกใช้ฟังก์ชั่น get_user ในไฟล์ inc/function.php
  $row  = $stmt->fetch();
  $numRow  = $stmt->rowcount();
  if ($numRow > 0) {
    $data['loged'] = true;
    
  }
  else{
    $data['loged'] = false;
    $data['login_page'] = 'line://app/1592239766-xxxx';
  }
  // $data['user'] = $iduser;
}
elseif ($action == 'getbook') {

  $dep_id = sprintf("%03d",get_depart_edoc($iduser)); // เรียกกใช้ฟังก์ชั่นดึงรหัสแผนก แล้วเพิ่มเลข 0 ให้ครบ 3 หลัก
  
  $url = 'http://xxx.xxx.xxx.xx/api/eoffice/api-doc.php';
  
    $data = array(
      'page'  => $page,
      's'     => $search,
      'dep'   => $dep_id
    );

    $params = http_build_query($data);
    $ch_address = curl_init();
    curl_setopt( $ch_address, CURLOPT_URL, $url );
    curl_setopt( $ch_address, CURLOPT_POSTFIELDS, $params );
    curl_setopt( $ch_address, CURLOPT_POST, true );
    curl_setopt( $ch_address, CURLOPT_RETURNTRANSFER, true);
    curl_setopt( $ch_address, CURLOPT_SSL_VERIFYPEER, false );
    $content = curl_exec( $ch_address );

    curl_close($ch_address);    
    $data['content'] = $content;

}
else{
  $data['err'] = $action;
}


// print json_encode($data,true);
echo json_encode($data);
?>