<?php
header('Content-Type: application/json');
include_once '../inc/connection.php';
include_once '../inc/function.php';

$iduser = $_POST['iduser'];
$action = $_POST['action'];
$today = date("Y-m-d H:i:s");

function ConvDate($convD) {
    $GGdate = substr($convD, 8, 2);
    $GGmonth = substr($convD, 5, 2);
    $GGyear = substr($convD, 0, 4)+543;
    $GGTime = substr($convD,-5);
    $Buffdate = $GGdate."/".$GGmonth."/".$GGyear.' เวลา '.$GGTime;
    return $Buffdate;
}


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
}
elseif ($action == 'insert_meeting') {
    $iduser   = $_POST['userid'];
    $idroom   = $_POST['meetroom'];
    $topic    = $_POST['topic'];
    $people   = $_POST['people'];
    $start_date = date('Y-m-d H:i',strtotime($_POST['start_date']));
    $end_date = date('Y-m-d H:i',strtotime($_POST['end_date']));
    $mark   = empty($_POST['mark']) ? " " : $_POST['mark'];

    $sql = "SELECT * FROM api_line_bookingroom WHERE status_booking = 1 AND id_room = '".$idroom."' AND 
  (
      ('".$start_date."' BETWEEN `start_date`AND `end_date`) OR 
      ('".$end_date."' BETWEEN `start_date`AND `end_date`) OR
      ( `start_date` BETWEEN  '".$start_date."' AND '".$end_date."' ) OR 
      ( `end_date` BETWEEN  '".$start_date."' AND '".$end_date."' )
  )";

    $stmt   = $dbcon->prepare($sql);
    $stmt->execute();
    $numRow   = $stmt->rowcount();

    $sql  = null;
    $stmt   = null;

    if ($numRow > 0) { //ตรวจสอบว่าจองห้องไปหรือยัง ถ้ามากกว่า 0 แสดงว่าจองไปแล้ว

      $data['status']  = false;
      $data['message'] = 'ไม่สามารถจองได้ เนื่องจากมีการจองไว้แล้ว';
    

    }else{ 

      $data_insert = [$iduser,$idroom,$topic,$people,$start_date,$end_date,$mark];

      $sql = "INSERT INTO api_line_bookingroom (id_user,id_room,topic,people,start_date,end_date,on_updated,on_created,mark) VALUES(?,?,?,?,?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?)";
      $stmt = $dbcon->prepare($sql);
      $result = $stmt->execute($data_insert);
      $LAST_ID = $dbcon->lastInsertId();

     if ($result) {

        $stmt   = get_detail_booking_room($LAST_ID); // เรียกใช้ฟังก์ชั่นดึงข้อมูลผู้จอง
        $row = $stmt->fetch();

$messages = array(
  'type' => 'flex',
  'altText' => 'จองห้องประชุมสำเร็จ',
  'contents' => array(
    'type' => 'bubble',
    'direction' => 'ltr',
    'header' => array(
      'type' => 'box',
      'layout' => 'vertical',
      'spacing' => 'xs',
      'margin' => 'xs',
      'contents' => array(
        array(
          'type' => 'spacer',
          'size' => 'xs'
        )
      )
    ),
    'hero' => array(
      'type' => 'image',
      'url' => 'https://praibuenghospital.org/img/logo.png',
      'margin' => 'none',
      'size' => 'sm',
      'aspectRatio' => '1:1',
      'aspectMode' => 'cover',
      'backgroundColor' => '#FDFDFD'
    ),
    'body' => array(
      'type' => 'box',
      'layout' => 'vertical',
      'spacing' => 'md',
      'margin' => 'none',
      'contents' => array(
        array(
          'type' => 'text',
          'text' => 'จองห้องประชุม',
          'margin' => 'none',
          'align' => 'center',
          'weight' => 'bold',
          'color' => '#000000'
        ),
        array(
          'type' => 'separator',
          'margin' => 'sm',
          'color' => '#BDB4B4'
        ),
        array(
          'type' => 'box',
          'layout' => 'baseline',
          'spacing' => 'xs',
          'margin' => 'sm',
          'contents' => array(
            array(
              'type' => 'text',
              'text' => 'เรื่อง',
              'flex' => 1,
              'margin' => 'md',
              'color' => '#BFBFBF'
            ),
            array(
              'type' => 'text',
              'text' => $topic,
              'flex' => 3,
              'align' => 'start',
              'weight' => 'bold',
              'color' => '#2C2C2C',
              'wrap' => true
            )
          )
        ),
        array(
          'type' => 'box',
          'layout' => 'baseline',
          'spacing' => 'xs',
          'margin' => 'sm',
          'contents' => array(
            array(
              'type' => 'text',
              'text' => 'ผู้เข้าร่วม',
              'flex' => 1,
              'color' => '#BFBFBF'
            ),
            array(
              'type' => 'text',
              'text' => $people.' คน',
              'flex' => 3,
              'align' => 'end',
              'weight' => 'bold',
              'color' => '#2C2C2C'
            )
          )
        ),
        array(
          'type' => 'box',
          'layout' => 'baseline',
          'spacing' => 'xs',
          'margin' => 'sm',
          'contents' => array(
            array(
              'type' => 'text',
              'text' => 'ห้อง',
              'flex' => 1,
              'color' => '#BFBFBF'
            ),
            array(
              'type' => 'text',
              'text' => $row["room_name"],
              'flex' => 3,
              'align' => 'end',
              'weight' => 'bold',
              'color' => '#2C2C2C'
            )
          )
        ),
        array(
          'type' => 'box',
          'layout' => 'baseline',
          'spacing' => 'xs',
          'margin' => 'sm',
          'contents' => array(
            array(
              'type' => 'text',
              'text' => 'เริ่ม',
              'flex' => 1,
              'color' => '#BFBFBF'
            ),
            array(
              'type' => 'text',
              'text' => ConvDate($start_date),
              'flex' => 3,
              'align' => 'end',
              'weight' => 'bold',
              'color' => '#2C2C2C'
            )
          )
        ),
        array(
          'type' => 'box',
          'layout' => 'baseline',
          'spacing' => 'xs',
          'margin' => 'sm',
          'contents' => array(
            array(
              'type' => 'text',
              'text' => 'สิ้นสุด',
              'flex' => 1,
              'color' => '#BFBFBF'
            ),
            array(
              'type' => 'text',
              'text' => ConvDate($end_date),
              'flex' => 3,
              'align' => 'end',
              'weight' => 'bold',
              'color' => '#2C2C2C'
            )
          )
        ),
        array(
          'type' => 'box',
          'layout' => 'baseline',
          'spacing' => 'xs',
          'margin' => 'xs',
          'contents' => array(
            array(
              'type' => 'text',
              'text' => 'ผู้จอง',
              'color' => '#BFBFBF'
            ),
            array(
              'type' => 'text',
              'text' => $row["fullname"],
              'flex' => 3,
              'align' => 'end',
              'weight' => 'bold',
              'color' => '#2C2C2C'
            )
          )
        ),
        array(
          'type' => 'text',
          'text' => 'หมายเหตุ',
          'color' => '#BFBFBF'
        ),
        array(
          'type' => 'text',
          'text' => $mark,
          'color' => '#D86363',
          'wrap' => true
        )
      )
    ),
    'footer' => array(
      'type' => 'box',
      'layout' => 'vertical',
      'margin' => 'sm',
      'contents' => array(
        array(
          'type' => 'text',
          'text' => 'โรงพยาบาลไพรบึง',
          'margin' => 'md',
          'align' => 'center',
          'weight' => 'bold',
          'color' => '#FEFFFF'
        )
      )
    ),
    'styles' => array(
      'header' => array(
        'backgroundColor' => '#FFFFFF'
      ),
      'footer' => array(
        'backgroundColor' => '#048955'
      )
    )
  )
);

        lineflexmessage($iduser,$messages); // เรียกใช้ฟังก์ชั่นส่ง Flex ส่งให้คนจอง

        $stmt   = get_manager_meetingroom();
        $result = $stmt->fetchAll();

        foreach ($result as $idroom) {
          lineflexmessage($idroom['id_linegroup'],$messages); // เรียกใช้ฟังก์ชั่นส่ง Flex ส่งให้ผู้ดูแลแต่ละห้อง
        }

        $data['status']  = true;
        $data['message'] = 'บันทึกเรียบร้อย';

      }
      else{
        $data['status']  = false;
        $data['message'] = 'ไม่สามารถจองได้ กรุณาติดต่อผู้ดูแลระบบ';
      }

    }

}
elseif ($action == 'get_booking') {


  $stmt_get   = get_bookingroom($iduser); //เรียกใช้ฟังก์ชั่น get_bookingroom
  $row_get  = $stmt_get->fetchAll();
  $numRow  = $stmt_get->rowcount();
  // $data['booking'] =  '';
  $data['booking'] = '<div class="card" style="margin-bottom: 0px;">
    <div class="card-header" style="background-color:#ffbb33;">
        <div class="card-head-row">
            <div class="card-title"><i class="icon-clock"></i> ประวัติการจองห้องประชุม</div>
        </div>
    </div>
    <div class="card-body">';

  if ($numRow > 0) {
      foreach ($row_get as $value) {
        $data['booking'] .= '<div class="d-flex">
            <div class="flex-1 ml-3 pt-1">
                <h6 class="text-uppercase fw-bold mb-1">'.$value['topic'].'</h6>
                <span class="text-muted">'.$value['room_name'].'</span><br/>
                <span class="text-muted">'.DateThai($value['start_date'],$value['end_date']).'</span><br/>
            </div>
            <div class="float-right pt-1">
                <small class="text-muted">';
                if ($value['start_date'] < $today) { // ตรวจสอบเงื่อนไขกรณีวันที่จองน้อยกว่าปัจจุบันให้แสดงเมนูแก้ไข
                  $data['booking'] .= time_elapsed_string($value['start_date']);
                }else{
                  $data['booking'] .= '<a class="text-danger edit" data-id="'.$value['id'].'"><i class="icon-close"></i> ยกเลิก</a>';
                }
                $data['booking'] .= '</small>
            </div>
        </div><div class="separator-dashed"></div>';
      }
  }
  else{

    $data['booking'] .= '<div class="d-flex">
    <div class="flex-1 ml-3 pt-1">
        <h6 class="text-uppercase fw-bold mb-1">ยังไม่มีข้อมูลการจอง</h6>
    </div>
</div>';
  }
  

  $data['booking'] .= '</div>
</div>';

}
elseif ($action == 'del_booking') {
    $id = $_POST['id_booking'];
    $data_insert = [$id];
    $sql = "UPDATE api_line_bookingroom SET status_booking = 0 WHERE id = ?";
    $stmt = $dbcon->prepare($sql);
    $result = $stmt->execute($data_insert);

    if ($result) {
      $data['del_booking'] = true;
      $data['del_mess'] = 'ยกเลิกการจองห้องประชุมเรียบร้อย';

    }
    else{
      $data['del_booking'] = false;
      $data['del_mess'] = 'ไม่สามารถยกเลิกการจองห้องประชุมได้ กรุณาติดต่อผู้ดูแลระบบ';
    }
}
else{ 
  $data['status']  = false;
  $data['message'] = 'ไม่สามารถจองได้ กรุณาติดต่อผู้ดูแลระบบครับ';
}

$sql  = null;
$stmt   = null;
echo json_encode($data);
?>