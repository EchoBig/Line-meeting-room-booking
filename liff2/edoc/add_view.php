<?php
header('Content-Type: application/json');
include_once '../inc/connection.php';
$idbook 	= $_POST['id'];
$filename 	= $_POST['filename'];
$iduser 	= $_POST['iduser'];


$sql = "INSERT INTO api_line_viewdoc (`id_user`,`idbook`,`on_created`)
SELECT * FROM (SELECT '$iduser','$idbook',CURRENT_TIMESTAMP) AS tmp
WHERE NOT EXISTS (
    SELECT `idbook` FROM api_line_viewdoc WHERE `idbook` = '$idbook'
) LIMIT 1;";
$stmt	= $dbcon->prepare($sql);
$result = $stmt->execute();

if ($result) {
	addview($idbook);
	$data['UpOK'] = true;
	$data['link'] = 'https://docs.google.com/viewer?url=http://xxx.xxx.xxx.xx/eoffice/administrator/fileupload/'.$filename;
}
else{
	$data['UpOK'] = false;
}

echo json_encode($data);

function addview($idbook){
  
  	$url = 'http://xxx.xxx.xxx.xx/api/eoffice/addviewbook.php'; //ส่งไปเพิ่มจำนวนวิวใน Eoffice  
    $data = array(
      'idbook'   => $idbook
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
}
?>