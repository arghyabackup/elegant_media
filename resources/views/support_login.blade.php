@extends('layouts.frontend')
@section('content')
<div class="container mt-2">
    <div class="row">
       <div class="col-md-12 mt-1 text-center">
          <h2 class="text-white bg-dark p-3">Support Login</h2>
       </div>
       @if(session('error'))
       <div class="col-md-12 mt-1 text-center">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
       </div>
        @endif
       <div class="col-md-6 offset-md-3 mb-4 mt-4">
            <form id="postForm" class="form-horizontal" method="POST">
                <input type="hidden" value="{{ route('support.postLogin') }}" name="action"> 
                @csrf
                <div class="form-group">
                 <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="">
                </div>
                <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="btn-save">Login</button>
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
        username: {
            required: true,
        },
        password: {
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
                //$('.view_ticket').html(resp.html_data);
                window.location.href = resp.url;
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