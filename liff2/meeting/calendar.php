
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href='../node_modules/@fullcalendar/core/main.css' rel='stylesheet' />
<link href='../node_modules/@fullcalendar/timeline/main.css' rel='stylesheet' />
<link href='../node_modules/@fullcalendar/resource-timeline/main.css' rel='stylesheet' />
<link href='../node_modules/@fullcalendar/list/main.css' rel='stylesheet' />

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
      weekNumbers: false,
      editable: false,
      eventLimit: false, // allow "more" link when too many events
      resourceLabelText: 'Rooms',
      resources: {url: 'resource.php?resource',},
      events: {url: 'resource.php?events'}
    });

    calendar.render();


  });

</script>

</head>
<body>

  <div id='calendar'></div>

</body>
</html>
