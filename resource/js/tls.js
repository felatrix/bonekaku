var dpa = $('#dpa').datepicker()
        .on('changeDate', function(ev){
            dpa.hide();
        }).data('datepicker');

var dpk = $('#dpk').datepicker()
        .on('changeDate', function(ev){
            dpk.hide();
        }).data('datepicker');

$("select").mousedown(function () {
    var selected = $(this).val();
    var myId = $(this).attr('id');
    var myEl = $(this);
    myOp = $(this).find('option').length;
    if(myId == 'jenis' || myId == 'input1' || myId == 'itemj'){
        switch(myId) {
            case 'jenis': theUrl = 'bbhn/clients'; break;
            case 'input1': theUrl = 'color/datas'; break;
            case 'itemj': theUrl = 'item/json'; break;
        }
        $.getJSON(theUrl, {id: $(this).val(), ajax: 'true'}, function (j) {
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