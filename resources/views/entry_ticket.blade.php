@extends('layouts.frontend')
@section('content')
<div class="container mt-2">
    <div class="row">
       <div class="col-md-12 mt-1 text-center">
          <h2 class="text-white bg-dark p-3">Ticket Submit</h2>
       </div>
       <div class="text-center menu">
            <ul>
                <li><a href="{{ route('home') }}" class="">View Ticket</a></li>
                <li><a href="{{ route('home.entryTicket') }}" class="">Submit Ticket</a></li>
                <li><a href="{{ route('support.login') }}" class="">Agent Login</a></li>
            </ul>
        </div>
       <div class="col-md-6 offset-md-3 mb-4 mt-4">
            <form id="postForm" class="form-horizontal" method="POST">
                <input type="hidden" value="{{ route('home.submitTicket') }}" name="action"> 
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name" value="">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="">
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="description" name="description" placeholder="Enter Problem Description"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="btn-save">Submit</button>
                </div>
           </form>
           <div class="alert-message"></div>
       </div>
    </div>
 </div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
  $("#postForm").validate({
    rules: {
        customer_name: {
            required: true,
        },
        email: {
            required: true,
            email: true,
        },
        phone: {
            required: true,
            number: true,
        },
        description: {
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
                if (resp.flag === true) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + resp.message,
                        time: 10,
                        position: 'top'
                    });
                    $('#postForm')[0].reset();
                    $('.alert-message').html('<p class="p-3 mt-3 alert-success">'+resp.message+'</p>');

                } else {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-times"></i> ' + resp.message,
                        time: 10,
                        position: 'top'
                    });
                }
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