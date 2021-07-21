$(document).ready(function() {
    const defaultLiffId = "1592239766-xxxx"; // change the default LIFF value if you are not using a node server
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
            window.alert('Error getting profile: ' + err);
        });
}

function getuserId() {
    liff.getProfile().then(function(profile) {
        var iduser = profile.userId; //ดึงค่าไอดีไลน์มาเก็บในตัวแปร
        $('#userid').val(iduser);
        check_user(profile.userId);
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
            }
        }
    });
}

// กรณีเลือกวันที่เริ่มก่อน ให้ทำการระบุค่าวันที่สุดท้ายด้วย
$(document).on('change', '#st_date', function() {
    var min = $('#st_date').val();
    document.getElementById("en_date").setAttribute("min", min);
});

// กรณีเลือกวันที่สุดท้ายก่อน ให้ทำการระบุค่าวันที่เริ่มด้วย
$(document).on('change', '#en_date', function() {
    var max = $('#en_date').val();
    document.getElementById("st_date").setAttribute("max", max);
});


$(document).on("submit", "#booking", function(event) { //เมื่อบันทึกข้อมูล
    event.preventDefault();
    $.ajax({
        url: 'action.php',
        type: 'post',
        data: $(this).serialize(),
        dataType: 'json',
        beforeSend: function() {
          $('#btn-booking').prop('disabled', true);
        },
        success: function(data) {
            // console.log(data);
            if (data.status == true) {
                swal("จองห้องประชุม", data.message, {
                    icon: "success",
                    showConfirmButton: false,
  					timer: 1500
                });
                $("#booking")[0].reset();
                $('#btn-booking').prop('disabled', false);
            } else {
                swal("จองห้องประชุม", data.message, {
                    icon: "error",
                    showConfirmButton: false,
  					timer: 1500
                });
            }
        }
    });

});

$(document).on('click','#tab-listbooking',function(e){
    e.preventDefault();
    var iduser = $('#userid').val();
    get_booking(iduser); //เรียกใช้ฟังก์ชั่นเมื่อกด tab 
});

function get_booking(iduser){
    $.ajax({
        url:'action.php',
        type:'post',
        data:{action:'get_booking',iduser:iduser},
        dataType:'json',
        success:function(data){
            // alert(data.booking);
            $('#my_booking').html(data.booking);
        }
    });
}

$(document).on('click','.edit',function(e){
    e.preventDefault();
    var id = $(this).attr('data-id');
    // alert(id);

    swal({
        // title: 'ต้องการยกเลิกการจองห้องประชุม?',
        text: "ต้องการยกเลิกการจองห้องประชุม?",
        type: 'warning',
        buttons:{
            confirm: {
                text : 'Yes!',
                className : 'btn btn-success'
            },
            cancel: {
                visible: true,
                className: 'btn btn-danger'
            }
        }
    }).then((Delete) => {
        if (Delete) {
            var iduser = $('#userid').val();
            $.ajax({
                url:'action.php',
                type:'post',
                data:{action:'del_booking',iduser:iduser,id_booking:id},
                dataType:'json',
                success:function(data){
                    if (data.del_booking == true) {
                        swal("ยกเลิกจองห้องประชุม", data.del_mess, {
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        get_booking(iduser);
                    } else {
                        swal("ยกเลิกจองห้องประชุม", data.del_mess, {
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
            // alert(id);
        } else {
            swal.close();
        }
    });

});