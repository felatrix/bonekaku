//$(document).ready(function() {
    var dpto = $('#dpto').datepicker()
            .on('changeDate', function(ev){
                dpto.hide();
            }).data('datepicker');
    var dptt = $('#dptt').datepicker()
            .on('changeDate', function(ev){
                dptt.hide();
            }).data('datepicker');
    var dptd = $('#dptd').datepicker()
            .on('changeDate', function(ev){
                dptd.hide();
            }).data('datepicker');
    var dptp = $('#dptp').datepicker()
            .on('changeDate', function(ev){
                dptp.hide();
            }).data('datepicker');
    var dptk = $('#dptk').datepicker()
            .on('changeDate', function(ev){
                dptk.hide();
            }).data('datepicker');
            
//});

var baseurl = document.getElementById('baseurl').value;

$('input.typeahead').typeahead({
    ajax: baseurl + 'client/clients',
    displayField: 'name',
    onSelect: displayResult
});

function displayResult(item) {
    $('#pemesanid').val(item.value);    
}

$("select").mousedown(function () {
    var selected = $(this).val();
    var myId = $(this).attr('id');
    var myEl = $(this);
    myOp = $(this).find('option').length;
    if(myId == 'i_nama' || myId == 'input99' || myId == 'jenis_tulisan'){
        switch(myId) {
            case 'i_nama': theUrl = 'client/clients'; break;
            case 'input99': theUrl = 'item/json'; break;
            case 'jenis_tulisan': theUrl = 'jenis/json'; break;
        }
        $.getJSON(baseurl + theUrl, {id: $(this).val(), ajax: 'true'}, function (j) {
            var options = '<option value="">Silahkan pilih</option>';
            for (var i = 0; i < j.length; i++) {
                selText = (selected == j[i].id) ? ' selected="selected"' : '';
                options += '<option value="' + j[i].id + '"' + selText + '>' + j[i].name + '</option>';
            }
            if((i+1) != myOp){
                myEl.html(options);
            }
        })
    }
});