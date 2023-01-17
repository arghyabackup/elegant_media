@extends('layouts.frontend')
@section('content')
<div class="container mt-2">
    <div class="row">
       <div class="col-md-12 mt-1 text-center">
          <h2 class="text-white bg-dark p-3">Support Ticket</h2>
       </div>
       <div class="text-center menu">
            <ul>
                <li><a href="{{ route('home') }}" class="">View Ticket</a></li>
                <li><a href="{{ route('home.entryTicket') }}" class="">Submit Ticket</a></li>
                <li><a href="{{ route('support.login') }}" class="">Agent Login</a></li>
            </ul>
        </div>
       <div class="col-md-12 mb-4 mt-4">
            <form id="postForm" class="form-horizontal" method="POST">
                <input type="hidden" value="{{ route('home.viewTicket') }}" name="action"> 
                @csrf
                <div class="row form-group">
                    <div class="col-md-2 text-center mt-3"><label>Ticket Reference number :</label></div>
                    <div class="col-md-4 mt-2">
                        <input type="text" class="form-control" name="reference_no" placeholder="Enter Reference number" value="">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary" id="btn-save">Submit</button>
                    </div>
                    <div class="col-md-2"></div>
                </div>
           </form>
       </div>
       <div class="col-md-12 mt-2 mb-3 view_ticket">
       </div>
    </div>
 </div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
  $("#postForm").validate({
    rules: {
        reference_no: {
            required: true,
        }
    },
    messages: {
    },
    submitHandler: function(form) {
        //form.submit();
        event.preventDefault();

        var formdata = jQuery('#postForm').serialize();
        var url = $('input[name=action]').val();
        var csrf_token = $('input[name=_token]').val();

        $.ajax({
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            url: url,
            data: formdata, // get all form field value in 
            dataType: 'json',
            success: function(resp) {
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> ' + resp.message,
                    time: 10,
                    position: 'top'
                });
                //$('#postForm')[0].reset();
                $('.view_ticket').html(resp.html_data);

            },
            error: function(resp) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-times"></i> ' + resp.responseJSON.message,
                    time: 10,
                    position: 'top'
                });

            }
        });
    }
  });

});
</script>
@endsection