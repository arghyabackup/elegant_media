function delete_data(id,check_field,table_name){
    var current_location=window.location.href;
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                jQuery.ajax({
                    url: base_url + 'page/delete_data',
                    type: 'POST',
                    data: {id: id, check_field: check_field, table_name: table_name},
                    dataType: 'json'
                })
                .done(function(response){
                    swal('Deleted!', response.message, response.status);
                    window.location.href=current_location;
                    window.location.reload();
                })
                .fail(function(){
                    swal('Oops...', 'Something went wrong with ajax !', 'error');
                });
            });
        },
        allowOutsideClick: false
    });
}

  
$(document).ready(function($) {
    $('.table').DataTable();

    $('body').on('click', '.edit', function() {
        var id = $(this).data('id');
        // ajax
        $.ajax({
            type: "POST",
            url: base_url + "page/gallery_view",
            data: {
                id: id
            },
            dataType: 'html',
            success: function(resp) {
                //console.log(resp)
                $('#user-model .modal-body').html(resp);
                $('#user-model').modal('show');
            }
        });
    });
});

$(document).ready(function(){
$('.image-popup-vertical-fit').magnificPopup({
    type: 'image',
  mainClass: 'mfp-with-zoom', 
  gallery:{
            enabled:true
        },

  zoom: {
    enabled: true, 

    duration: 300, // duration of the effect, in milliseconds
    easing: 'ease-in-out', // CSS transition easing function

    opener: function(openerElement) {

      return openerElement.is('img') ? openerElement : openerElement.find('img');
  }
}

});

});