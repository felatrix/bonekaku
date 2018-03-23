//turn to inline mode
//$.fn.editable.defaults.mode = 'inline';
//
//$(document).ready(function() {
//    $('#username').editable();
//});

$(document).ready(function(){
  $(".del-confirm").click(function(){
    if (!confirm("Yakin akan dihapus?")){
      return false;
    }
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

$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
});

//window.onunload = refreshParent;
//function refreshParent() {
//    window.opener.location.reload();
//}

$(document).ready(function(){
    $("tr:has(#unread)").mouseenter(function() {
        status = $("#unread",this).text();
        if(status == 'Baru'){
            var myEl = $(this);
            token = $("input[name='csrf_token']",this).val();
            if($("#unread",this).hasClass("main")){ ctlr = 'main' }
            else if($("#unread",this).hasClass("cut")){ ctlr = 'cut' }
            else if($("#unread",this).hasClass("prtl")){ ctlr = 'prtl' }
            else if($("#unread",this).hasClass("sew")){ ctlr = 'sew' }
            else ctlr = 'fin'
            oid = $("input[name='oid']",this).val();
            coid = ($(":has(input[name='coid'])",this)) ? $("input[name='coid']",this).val() : '';
            timer = setTimeout(function(){
                $.ajax({
                    method: "POST",
                    url: "ajaxset",
                    data: { oid: oid, ctlr: ctlr, coid: coid, csrf_token: token}
                })
                .done(function( msg ) {
                    console.log( "Data Saved: " + msg );
                    $("#unread",myEl).remove();
                })
            }, 2000);
        }
    }).mouseleave(function(){
        clearTimeout(timer);
    });    
});
