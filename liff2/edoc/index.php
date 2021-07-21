<?php
include_once '../inc/connection.php';
include_once '../inc/function.php';

setlocale(LC_TIME, 'Thai');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ดูหนังสือ</title>
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
    <style>
        .preloader-my {
            display: none;
           position: absolute;
           top: 80px;
           left: 0;
           width: 100%;
           height: 100%;
           z-index: 9999;
           background-image: url('loader.gif');
           background-repeat: no-repeat; 
           background-color: #FFF;
           background-position: center;
        }
    </style>
</head>

<body data-background-color="bg3">
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="red2">
                <a class="text-white mx-auto fw-bold"><i class="icon-docs"></i>
                    E-Document
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
                    <div class="card full-height">
                        <div class="card-header">
                            <form class="navbar-form nav-search mr-md-3" id="frm-search">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="submit" class="btn btn-search pr-1">
                                            <i class="fa fa-search search-icon"></i>
                                        </button>
                                    </div>
                                    <input type="text" id="txtsrch" placeholder="Search ..." class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="preloader-my"></div>
                            
                            <div id="showbook"></div>
                                <div id="pagination"></div>
                            <ul class="pagination pg-primary float-right">
                                <input type="hidden" id="c_page">
                                <span id="previous"></span>
                                <span id="next"></span>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End container -->
            </div>
            <!-- End content -->
        </div>
        <!-- End main-panel -->
        <input type="hidden" name="userid" id="userid">

<!-- Modal -->
<div class="modal fade" id="veiwfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ดูหนังสือ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
            <table class="table table-borderless table-sm table-striped">
              <thead>
                <tr class="table-info">
                  <th scope="col">หัวข้อ</th>
                  <th scope="col">รายละเอียด</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                    <th scope="row">เรื่อง</th>
                    <td><span id="topic"></span></td>
                </tr>
                <tr>
                    <th scope="row">เลขที่หนังสือ</th>
                    <td><span id="idbook"></span></td>
                </tr>
                <tr>
                    <th scope="row">วันที่รับ</th>
                    <td><span id="date_recieve"></span></td>
                </tr>
                <tr>
                    <th scope="row">รับจาก</th>
                    <td><span id="rc_from"></span></td>
                </tr>
                <tr>
                    <th scope="row">ดูหนังสือ</th>
                    <td>
                        <a href="#" data-file="" data-id="" id="view_book">ดูหนังสือ</a>
                    </td>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

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
    <!-- Atlantis JS -->
    <script src="../js/atlantis.min.js"></script>
    <script>
        $(document).ready(function(){

            const defaultLiffId = "1592239766-xxxxxx"; // change the default LIFF value if you are not using a node server
            // DO NOT CHANGE THIS
            let myLiffId = "";
            myLiffId = defaultLiffId;
            initializeLiff(myLiffId);

        });

        function initializeLiff(myLiffId) {
            liff
            .init({
                liffId: myLiffId
            })
            .then(() => {
                // start to use LIFF's api
                getuserId();
            })
            .catch((err) => {
                window.alert('Error getting profile2: ' + err);
            });
        }

            function getuserId() {
                liff.getProfile().then(function(profile) {
                    var iduser = profile.userId; //ดึงค่าไอดีไลน์มาเก็บในตัวแปร
                    $('#userid').val(iduser);
                    check_user(profile.userId);
                    // alert(iduser);
                }).catch(function(error) {
                    window.alert('Error getting profile: ' + error);
                });
            }

            function check_user(userid) {
                $.ajax({
                    url: 'action.php',
                    method: 'POST',
                    data: { action: 'checkuser', iduser: userid },
                    dataType: 'json',
                    success: function(data) {
                        if (data.loged == false) {
                            liff.closeWindow();
                            liff.openWindow({
                                url: data.login_page
                            });
                        }else{
                            // alert('passok');
                            loadbook(1,'',userid);
                        }
                    }
                });
            }


            function loadbook(p,s,u){
                var sh = '';
                $.ajax({
                    url:'action.php',
                    type:'post',
                    data:{p:p,s:s,iduser:u,action:'getbook'},
                    dataType:'json',
                    beforeSend: function() {
                        $('.preloader-my').css('display','flex');
                    },
                    success:function(data){
                        $('.preloader-my').css('display','none');
                        var bookObj = JSON.parse(data.content);
                        if (bookObj.listbook.length > 0) {

                            $('#c_page').val(bookObj.current_page);

                            $.each(bookObj.listbook,function(k,v){                            
                            sh += '<div class="d-flex"><div class="flex-1"><a class="text-muted view" data-id="'+v.id+'" data-title="'+v.name_edoc+'" data-daterc="'+v.regdate+'" data-bookid="'+v.id_edoc+'" data-rcfrom="'+v.receive+'" data-file="'+v.fileupload+'">'+v.name_edoc+'<span class="text-danger pl-1"><i class="fas fa-search-plus pr-1" ></i>'+v.view+'</span></a></div><div class="float-right pt-1"><small class="text-muted text-primary">'+v.regdate+'</small></div></div><div class="separator-dashed"></div>';
                            }); //Loop show book


                            if (bookObj.current_page > 1) {
                                $('#previous').html('<li class="page-item" id="p-page"><a class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></a></li>');
                            }else{
                                $('#previous').html('');
                            }

                            if (bookObj.total_page > 1 && bookObj.current_page < bookObj.total_page) {
                                $('#next').html('<li class="page-item" id="n-page"><a class="page-link" aria-label="Next"><span aria-hidden="true">»</span></a></li>');
                            }else{
                                $('#next').html('');
                            }


                        }else{
                            sh += 'ไม่มีข้อมูล';
                        }        
                        $('#showbook').html(sh);
                    },
                    error:function(err){
                        console.log(err);
                    }
                });
            };
            // End Function fetch

            $(document).on('submit','#frm-search',function(e){
                e.preventDefault();
                var txtsrch = $('#txtsrch').val();
                var iduser  = $('#userid').val();
                loadbook(1,txtsrch,iduser);
            });// Search Book

            $(document).on('click','#p-page',function(){
                let current_p = $('#c_page').val();
                var txtsrch = $('#txtsrch').val();
                var iduser  = $('#userid').val();
                loadbook((--current_p),txtsrch,iduser);
            });

            $(document).on('click','#n-page',function(){
                let current_p = $('#c_page').val();
                var txtsrch = $('#txtsrch').val();
                var iduser  = $('#userid').val();
                loadbook((++current_p),txtsrch,iduser);
            });

            $(document).on('click','.view',function(){
                var id = $(this).attr('data-id');
                var title = $(this).attr('data-title');
                var bookid = $(this).attr('data-bookid');
                var regdate = $(this).attr('data-daterc');
                var rcfrom = $(this).attr('data-rcfrom');
                var file = $(this).attr('data-file');
                $('#topic').text(title);
                $('#idbook').text(bookid);
                $('#date_recieve').text(regdate);
                $('#rc_from').text(rcfrom);
                $('#view_book').attr('data-id',id);
                $('#view_book').attr('data-file',file);
                $('#veiwfile').modal('show');
            });

            $(document).on('click','#view_book',function(){
                var id = $(this).attr('data-id');
                var filename = $(this).attr('data-file');
                var iduser  = $('#userid').val();
                    
                $.ajax({
                    url:'add_view.php',
                    method:'post',
                    data:{ id:id,filename:filename,iduser:iduser },
                    dataType:'json',
                    success:function(data){
                        if (data.UpOK == true) {
                            liff.openWindow({
                                url: data.link,
                                external:true
                            });
                        }
                        else{
                            alert('Can not read file');
                        }
                    }
                });

            });


    </script>

</body>

</html>