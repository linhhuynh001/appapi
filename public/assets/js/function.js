function submit_edit(id){
    $.ajax({
        type : 'get',
        url : 'general/'+id+'/edit',
        dataType : 'json',
        data : { id : id },
        success:function(data){
            $('#view_update_title').html(data);
        }
    });
}