<script src="../core/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../plugins/datetimepicker/jquery.datetimepicker.js"></script>
<script src="core/js/yepnope.min.js" type="text/javascript" charset="utf-8"></script>
<script src="core/js/default.js" type="text/javascript" charset="utf-8"></script>
<!-- <script src="./core/js/fastdom/index.js" type="text/javascript" charset="utf-8" async defer></script> -->
<script src="../plugins/bootstrap/js/bootstrap.min.js" type="text/javascript" charset="utf-8" ></script>



<link rel="stylesheet" type="text/css" href="../plugins/summernote/summernote.css">
<script src="../plugins/summernote/summernote.min.js" type="text/javascript" charset="utf-8"  ></script>
<script>
  $('.ckeditor').summernote({
    height: "300px"
  });
</script>

<link rel="stylesheet" type="text/css" href="../plugins/datetimepicker/jquery.datetimepicker.css">

<script>
 /* jquery.datepicker inits */
 $('.datepicker').datetimepicker({
   lang:'pt',
   timepicker:false,
   format:'Y-m-d',
   closeOnDateSelect: true
 });

 $('.datetimepicker').datetimepicker({
   lang:'pt',
   timepicker:true,
   format:'Y-m-d H:i',
   closeOnDateSelect: true,
   allowTimes:[
   '00:00','00:15', '00:30', '00:45','01:00',
   '01:15', '01:30', '01:45','02:00',
   '02:15', '02:30', '02:45','03:00',
   '03:15', '03:30', '03:45','04:00',
   '04:15', '04:30', '04:45','05:00',
   '05:15', '05:30', '05:45','06:00',
   '06:15', '06:30', '06:45','07:00',
   '07:15', '07:30', '07:45','08:00',
   '08:15', '08:30', '08:45','09:00',
   '09:15', '09:30', '09:45','10:00',
   '10:15', '10:30', '10:45','11:00',
   '11:15', '11:30', '11:45','12:00',
   '12:15', '12:30', '12:45','13:00',
   '13:15', '13:30', '13:45','14:00',
   '14:15', '14:30', '14:45','15:00',
   '15:15', '15:30', '15:45','16:00',
   '16:15', '16:30', '16:45','17:00',
   '17:15', '17:30', '17:45','18:00',
   '18:15', '18:30', '18:45','19:00',
   '19:15', '19:30', '19:45','20:00',
   '20:15', '20:30', '20:45','21:00',
   '21:15', '21:30', '21:45','22:00',
   '22:15', '22:30', '22:45','23:00',
   '23:15', '23:30', '23:45'
   ]
 });

 $('.timepicker').datetimepicker({
   lang:'pt',
   datepicker:false,
   allowTimes:[
   '00:00','00:15', '00:30', '00:45','01:00',
   '01:15', '01:30', '01:45','02:00',
   '02:15', '02:30', '02:45','03:00',
   '03:15', '03:30', '03:45','04:00',
   '04:15', '04:30', '04:45','05:00',
   '05:15', '05:30', '05:45','06:00',
   '06:15', '06:30', '06:45','07:00',
   '07:15', '07:30', '07:45','08:00',
   '08:15', '08:30', '08:45','09:00',
   '09:15', '09:30', '09:45','10:00',
   '10:15', '10:30', '10:45','11:00',
   '11:15', '11:30', '11:45','12:00',
   '12:15', '12:30', '12:45','13:00',
   '13:15', '13:30', '13:45','14:00',
   '14:15', '14:30', '14:45','15:00',
   '15:15', '15:30', '15:45','16:00',
   '16:15', '16:30', '16:45','17:00',
   '17:15', '17:30', '17:45','18:00',
   '18:15', '18:30', '18:45','19:00',
   '19:15', '19:30', '19:45','20:00',
   '20:15', '20:30', '20:45','21:00',
   '21:15', '21:30', '21:45','22:00',
   '22:15', '22:30', '22:45','23:00',
   '23:15', '23:30', '23:45'
   ],
   format:'H:i'
 });


 $('.datepicker').on('change', function(event) {
  var data = new Date($(this).val());
  data.setDate(data.getDate()+1);

  $(this).parent().find('.info').html("<i> Dia "+(data.getDate())+" do "+(data.getMonth()+1)+" de "+(data.getFullYear())+"</i>");
});

 $('.datetimepicker').on('change', function(event) {
  var data = new Date($(this).val());
  var texto = "<i> Dia "+(data.getDate())+" do "+(data.getMonth()+1)+" de "+(data.getFullYear())+" as "+(data.getHours())+":";
  texto += (data.getMinutes() > 9) ? ""+data.getMinutes() : ""+data.getMinutes()+"0";
  texto += "</i>"
  $(this).parent().find('.info').html(texto);
});

</script>

</body>
</html>