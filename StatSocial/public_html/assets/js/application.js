$(function(){
    $('[data-toggle=tooltip]').tooltip();
    $('[data-toggle=popover]').popover({placement:'auto top', content: 'Helaas geen data...'});
    $(document).on("hidden.bs.modal",function(e){$(e.target).removeData("bs.modal").find(".modal-content").empty();});
    $(document).on('submit','form[data-async]',function(){$.ajax({type:"POST",url:$(this).attr('action'),data:$(this).serialize(),success:function(){window.location.reload();}});return false;})
});