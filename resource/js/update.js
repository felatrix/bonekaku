$("select").mousedown(function () {
    var selected = $(this).val();
    var myId = $(this).attr('id');
    var myEl = $(this);
    myOp = $(this).find('option').length;
    if(myId == 'jenis' || myId == 'warna'){
        switch(myId) {
            case 'jenis': theUrl = 'bbhn/clients'; break;
            case 'warna': theUrl = 'color/datas'; break;
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