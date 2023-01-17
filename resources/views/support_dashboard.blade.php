@extends('layouts.frontend')
@section('content')
<div class="container mt-2 mb-5">
    <div class="row">
        <div class="col-md-12 mt-1 text-center">
            <h2 class="text-white bg-dark p-3">Ticket Listing</h2>
        </div>
        <div class="text-right mt-2">
            <a href="{{ route('support.logout') }}" class="btn btn-primary">Logout</a>
        </div>
        <div class="col-md-12 mb-5 mt-5">
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" width="5%">#</th>
                        <th scope="col" width="13%">Reference No.</th>
                        <th scope="col" width="13%">Name</th>
                        <th scope="col" width="13%">Email</th>
                        <th scope="col" width="13%">Phone</th>
                        <th scope="col" width="13%">Status</th>
                        <th scope="col" width="30%" align="center">Action</th>
                    </tr>
                </thead>
                <tbody class="list_data">
                    @if($tickets)
                    @foreach($tickets as $key => $event)
                    <tr class="row_{{ $event->id }} post" data-id="{{ $event->id }}">
                        <td>{{ ++$key }}</td>
                        <td>{{ $event->reference_no }}</td>
                        <td>{{ $event->customer_name }}</td>
                        <td>{{ $event->email }}</td>
                        <td>{{ $event->phone }}</td>
                        <td class="status">@if($event->status == '2')
                            Closed
                            @elseif($event->status == '3')
                            Resolved
                            @elseif($event->status == '4')
                            Cancelled
                            @elseif($event->status == '1')
                            Opened
                            @else
                            New
                            @endif
                        </td>
                        <td>
                            @if($event->status == '2')
                            <a href="javacript:void(0)" id="clickView" class="btn btn-info" data-status="{{ $event->status }}" data-id="{{ $event->id }}"
                                data-link="{{ route('support.view', ['id' => $event->id]) }}">View</a>
                            @else
                            <a href="javacript:void(0)" id="clickView" class="btn btn-info" data-status="{{ $event->status }}" data-id="{{ $event->id }}"
                                data-link="{{ route('support.view', ['id' => $event->id]) }}">View</a>
                            <a href="javacript:void(0)" id="clickReply" class="btn btn-info"
                                data-id="{{ $event->id }}">Reply</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr class="no_data">
                        <td colspan="7" rowspan="1" headers="">No Data Found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="user-model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userModel">Reply Ticket</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <form id="postForm" class="form-horizontal" method="POST">
                    <input type="hidden" value="{{ route('support.reply') }}" name="action">
                    <input type="hidden" value="" name="ticket_id" id="ticket_id">
                    <input type="hidden" value="{{ $user_id }}" name="agent_id" id="agent_id">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" id="description" name="description"
                            placeholder="Enter Problem Description"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="btn-save">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="view-model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userModel">Ticket Details</h4>
                <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
$(document).ready(function($) {
    $('body').on('click', '#clickReply', function() {
        var id = $(this).data('id');
        $('#ticket_id').val(id);
        $('#user-model').modal('show');
    });
    $('body').on('click', '#clickView', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var self = $(this),
            item = self.parents('tr'),
            link = self.data('link');

        // ajax
        $.ajax({
            type: "GET",
            url: link,
            dataType: 'html',
            success: function(resp) {
                //console.log(resp)
                $('#view-model .modal-body').html(resp);
                $('#view-model').modal('show');

                if(status == '0'){
                    $('.list_data .row_'+id+' .status').html('Opened');
                }
            }
        });
    });
    $("#postForm").validate({
        rules: {
            description: {
                required: true,
            }
        },
        messages: {},
        submitHandler: function(form) {
            //form.submit();
            event.preventDefault();

            var id = jQuery('#ticket_id').val();
            var formdata = jQuery('#postForm').serialize();
            var url = $('input[name=action]').val();
            var csrf_token = $('input[name=_token]').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
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
                        $('#user-model').modal('hide');
                        $('.list_data .row_'+id+' #clickReply').hide();
                        $('.list_data .row_'+id+' .status').html('Closed');
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
                        text: '<i class="fa fa-times"></i> ' + resp.responseJSON
                            .message,
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