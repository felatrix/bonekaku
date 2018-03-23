var dpa = $('#dpa').datepicker()
        .on('changeDate', function(ev){
            dpa.hide();
            var text = $("#tgl").val();
            $("[name=bln]").val(text);
        }).data('datepicker');
        
$('.input-daterange').datepicker({
    format: "yyyy-mm-dd",
    daysOfWeekHighlighted: "0",
    autoclose: true,
    todayHighlight: true
});

var dp = $('#datepicker').datepicker()
        .on('changeDate', function(ev){
            var textStart = $("#start").val();
            var textEnd = $("#end").val();
            $("[name=starth]").val(textStart);
            $("[name=endh]").val(textEnd);
        }).data('datepicker');

var dp2 = $('#datepicker2').datepicker()
        .on('changeDate', function(ev){
            var textStart = $("#start2").val();
            var textEnd = $("#end2").val();
            $("[name=starth]").val(textStart);
            $("[name=endh]").val(textEnd);
        }).data('datepicker');

$( "select[name='pekerjas']" ).change(function() {
    $("input[name=pekerja]").val($(this).val());
});