<?php
include_once '../inc/connection.php';
include_once '../inc/function.php';

setlocale(LC_TIME, 'Thai');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>จองห้องประชุม</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!-- Fonts and icons -->
    <script src="../js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../css/fonts.min.css']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/atlantis.min.css">

    <!-- CSS calendar -->
    <link href='../node_modules/@fullcalendar/core/main.css' rel='stylesheet' />
    <link href='../node_modules/@fullcalendar/timeline/main.css' rel='stylesheet' />
    <link href='../node_modules/@fullcalendar/resource-timeline/main.css' rel='stylesheet' />
    <link href='../node_modules/@fullcalendar/list/main.css' rel='stylesheet' />

</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="blue">
                <a class="text-white mx-auto fw-bold">
                    Meeting Room
                </a>
            </div>
            <!-- End Logo Header -->
        </div>
        <!-- main-panel -->
        <div class="main-panel">
            <!-- content -->
            <div class="content">
                <!-- container -->
                <div class="container">
                    <div class="mt-2"></div>
                    <div class="card">
                        <div class="card-body" style="padding: 0px 0px;">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" style="padding: 10px;" data-toggle="pill" href="#pills-booking" role="tab" aria-controls="pills-booking" aria-selected="true"><i class="icon-plus"></i> จองห้อง</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-calendar" style="padding: 10px;" data-toggle="pill" href="#pills-calendar" role="tab" aria-controls="pills-calendar" aria-selected="false"><i class="icon-calendar"></i> ตารางประชุม</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" style="padding: 10px;" data-toggle="pill" id="tab-listbooking" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"><i class="icon-menu"></i> รายการของฉัน</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="padding: -10px;" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-booking" role="tabpanel" aria-labelledby="pills-booking-tab">
                                    <form id="booking">
                                        <?php
                                        $stmt   = fetch_meeting_room(); 
                                        $row    = $stmt->fetchAll();
                                        ?>
                                        <div class="col-md-12">
                                            <div class="form-group form-floating-label">
                                                <select class="form-control input-border-bottom" name="meetroom" required="true">
                                                    <option value="">เลือกห้องประชุม</option>
                                                    <?php foreach ($row as $key => $mroom): ?>
                                                    <option value="<?php echo $mroom['id'];?>">
                                                        <?php echo $mroom['room_name'];?>
                                                    </option>
                                                    <?php 
                                                        endforeach;
                                                        unset($mroom);
                                                        $stmt = null;
                                                        $row = null;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group form-floating-label">
                                                <input type="text" class="form-control input-border-bottom" required="true" name="topic">
                                                <label for="inputFloatingLabel" class="placeholder"><i class="icon-cursor"></i> หัวข้อการประชุม</label>
                                            </div>
                                            <div class="form-group form-floating-label">
                                                <input type="number" class="form-control input-border-bottom" required="true" name="people">
                                                <label for="inputFloatingLabel" class="placeholder"><i class="icon-people"></i> จำนวนผู้เข้าร่วม</label>
                                            </div>
                                            <div class="form-group form-floating-label">
                                                <input class="form-control input-border-bottom" type="datetime-local" value="<?php echo strftime('%Y-%m-%dT%H:%M', time());?>" required="true" name="start_date" id="st_date">
                                                <label for="inputFloatingLabel" class="placeholder"><i class="icon-clock"></i> เวลาเริ่ม</label>
                                            </div>
                                            <div class="form-group form-floating-label">
                                                <input class="form-control input-border-bottom" type="datetime-local" value="<?php echo strftime('%Y-%m-%dT%H:%M', time());?>" required="true" name="end_date" id="en_date">
                                                <label for="inputFloatingLabel" class="placeholder"><i class="icon-clock"></i> เวลาสิ้นสุด</label>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control input-border-bottom" placeholder="หากต้องการเพิ่มเติม" name="mark"></textarea>
                                            </div>
                                            <input type="hidden" name="action" value="insert_meeting">
                                            <input type="hidden" name="userid" id="userid">
                                            <button class="btn btn-primary btn-block" type="submit" style="margin-bottom: 10px;">
                                                <span class="btn-label">
                                                    <i class="fa fa-save"></i>
                                                </span>
                                                บันทึก
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="pills-calendar" role="tabpanel" style="min-height: 500px;">
                                    <div id='calendar' style="padding-top: 10px;"></div>
                                </div>
                                <div class="tab-pane fade" id="pills-contact">
                                    <div id="my_booking"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End container -->
            </div>
            <!-- End content -->
        </div>
        <!-- End main-panel -->
    </div>
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <!--   Core JS Files   -->
    <!-- <script src="../js/jquery-3.3.1.min.js"></script> -->
    <script src="../js/core/jquery.3.2.1.min.js"></script>
    <script src="../js/core/popper.min.js"></script>
    <script src="../js/core/bootstrap.min.js"></script>
    <!-- jQuery UI -->
    <script src="../js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <!-- <script src="../js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script> -->
    <!-- jQuery Scrollbar -->
    <!-- <script src="../js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script> -->

    <!-- Sweet Alert -->
    <script src="../js/plugin/sweetalert/sweetalert.min.js"></script>
    <!-- Calendar JS -->
    <script src='../node_modules/@fullcalendar/core/main.js'></script>
    <script src='../node_modules/@fullcalendar/core/locales/th.js'></script>
    <script src='../node_modules/@fullcalendar/timeline/main.js'></script>
    <script src='../node_modules/@fullcalendar/resource-common/main.js'></script>
    <script src='../node_modules/@fullcalendar/resource-timeline/main.js'></script>
    <script src='../node_modules/@fullcalendar/daygrid/main.js'></script>
    <script src='../node_modules/@fullcalendar/list/main.js'></script>
    <script>


  document.addEventListener('DOMContentLoaded', function() {

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy+'-'+mm+'-'+dd;

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'resourceTimeline','dayGrid', 'list' ],
      schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',//ระบุ license ว่าเราใช้งาน license ประเภทใด
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'resourceTimelineDay,dayGridMonth,listMonth'
      },
      defaultDate: today,
      defaultView: 'resourceTimeline',
      locale: 'th',
      buttonIcons: true, // show the prev/next text
      resourceLabelText: 'Rooms',
      resources: {url: 'resource.php?resource',},
      events: {url: 'resource.php?events'}
    });

    calendar.render();


  });

</script>

    <script src="../js/meeting/custom.js"></script>
    <!-- Atlantis JS -->
    <script src="../js/atlantis.min.js"></script>
</body>

</html>