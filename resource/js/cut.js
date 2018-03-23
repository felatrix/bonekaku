var dpto = $('#dpto').datepicker()
        .on('changeDate', function(ev){
            dpto.hide();
        }).data('datepicker');

var dpta = $('#dpta').datepicker()
        .on('changeDate', function(ev){
            dpta.hide();
        }).data('datepicker');

var baseurl = document.getElementById('baseurl').value;

$('input.typeahead').typeahead({
    ajax: baseurl + 'pekerja/json',
    displayField: 'name',
    onSelect: displayResult
});

function displayResult(item) {
    $('#pekerjaid').val(item.value);    
}

$("#i_nama").mousedown(function () {
    var selected = $(this).val();
    var myEl = $(this);
    theUrl = 'pekerja/json';
    myOp = $(this).find('option').length;
    $.getJSON(baseurl + theUrl, {id: $(this).val(), ajax: 'true'}, function (j) {
        var options = '<option value="">Silahkan pilih</option>';
        for (var i = 0; i < j.length; i++) {
            selText = (selected == j[i].id) ? ' selected="selected"' : '';
            options += '<option value="' + j[i].id + '"' + selText + '>' + j[i].name + '</option>';
        }
        if((i+1) != myOp){
            myEl.html(options);
        }        
    });
});