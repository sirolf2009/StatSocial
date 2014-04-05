$(function(){
    $('[data-toggle=tooltip]').tooltip();
    $('[data-toggle=popover]').popover({placement:'auto top', content: 'Helaas geen data...'});
    $(document).on("hidden.bs.modal",function(e){$(e.target).removeData("bs.modal").find(".modal-content").empty();});
    $(document).on('submit','form[data-async]',function(){$.ajax({type:"POST",url:$(this).attr('action'),data:$(this).serialize(),success:function(){window.location.reload();}});return false;});
    $(document).on('submit', 'form[data-type="ajax-form"]', function(){
        $.ajax({type:"POST",
        url:$(this).attr('action'),
        data:$(this).serialize(),
        beforeSend:function(){$('#ajax-table > tbody').html('<tr><td colspan="'+$('#ajax-table > thead > tr > th').length+'"><p class="alert alert-info">Filter wordt toegepast, een moment geduld... <i class="glyphicon glyphicon-refresh icon-refresh-animate"></i></p></td></tr>');},
        success:function(data){$('#ajax-table > tbody').html(data);}});
    return false;});
});