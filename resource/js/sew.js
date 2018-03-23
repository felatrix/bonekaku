//turn to inline mode
$.fn.editable.defaults.mode = 'inline';

$(document).ready(function() {
    $('.xtarget').editable({
        format: 'yyyy-mm-dd',    
        viewformat: 'yyyy-mm-dd',    
        datepicker: {
            weekStart: 1
        },
        ajaxOptions: {
           dataType: 'json' //assuming json response
        },   
        success: function(data, config) {
           if(data && data.id) {  //record created, response like {"id": 2}
               //set pk
               $(this).editable('option', 'pk', data.id);
           } else if(data && data.errors){ 
               //server-side validation error, response like {"errors": {"username": "username already exist"} }
               config.error.call(this, data.errors);
           } else if(data && data.up){
               location.reload();
           }               
       },        
    });
});

showConfirmation = function(title, message, success, cancel) {
    title = title ? title : 'Anda yakin?';
    var modal = $("#delModal");
    modal.find(".modal-title").html(title).end()
        .find(".modal-body").html(message).end()
        .modal({ backdrop: 'static', keyboard: false })
        .on('hidden.bs.modal', function () {
        modal.unbind();
    });
    if (success) {
        modal.one('click', '.modal-footer .btn-primary', success);
    }
    if (cancel) {
        modal.one('click', '.modal-header .close, .modal-footer .btn-default', cancel);
    }
};

$(document).on("click", ".delete-event", function(event){
    event.preventDefault();
    var self = $(this);
    var form = $(this).closest('form');
    var success = function(){
        form.trigger('submit');
    }
    var cancel = function(){
        $('#delModal').modal('hide');
    };
    if (self.data('confirmation')) {
        var title = self.data('confirmation-title') ? self.data('confirmation-title') : undefined;
        var message = self.data('confirmation');
        showConfirmation(title, message, success, cancel);
    } else {
        success();
    }
});
