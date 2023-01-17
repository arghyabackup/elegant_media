<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Models\Tickets;
use App\Models\TicketNotes;


class HomeController extends Controller{
    public function index()
    {   
        $data = [];
    	return view('home')->with($data);
    }
    public function entry_ticket()
    {   
        $data = [];
    	return view('entry_ticket')->with($data);
    }
    public function submit_ticket(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['required', 'nullable', 'string', 'max:32'],
            'description' => ['required', 'string', 'max:500']
        ]);
        $errors = $validator->errors();

        if($validator->fails()) {
            return response()->json(['flag' => false, 'message' => $errors->first()], 422);
        }
        try {
            $reference_no = mt_rand(10000000,99999999);
            $ticketData = Tickets::create([
                'reference_no'  => $reference_no,
                'customer_name' => $request->input('customer_name'),
                'email' => $request->input('email'),
                'phone' => trim($request->input('phone')),
                'description' => $request->input('description')
            ]);
            
            // $mail_data = '<div class="row">
            //     <p><b>Name :</b> '.$request->input('customer_name').'</p>
            //     <p><b>Email :</b> '.$request->input('email').'</p>
            //     <p><b>Phone No. :</b> '.$request->input('phone').'</p>
            //     <p><b>Comments :</b></p><br>
            //     <p>'.$request->input('description').'</p>
            //     </div>';

            // $details = [
            //     'title' => 'Ticket Support #'.$reference_no,
            //     'body' => $mail_data
            // ];
            // Mail::to($request->input('email'))->send(new \App\Mail\ticketSupportMail($details));

            return response()->json([
                'flag' => true,
                'message' => 'Ticket successfully Submitted! Your Ticket reference number is '.$reference_no,
            ], 202);
        } catch (\Exception $e) {
            return response()->json(['flag' => false, 'message' => $e->getMessage()], 422);
        }
    }
    public function view_ticket(Request $request){
        $validator = Validator::make($request->all(), [
            'reference_no' => ['required', 'string', 'max:100']
        ]);
        $errors = $validator->errors();

        if($validator->fails()) {
            return response()->json(['flag' => false, 'message' => $errors->first()], 422);
        }
        try {
            $get_data = Tickets::select('*')->where('reference_no',$request->input('reference_no'))->where('is_deleted', '0')->first();

            if($get_data){

                if($get_data->status == '2'){
                    $status_data = 'Closed';
                }elseif($get_data->status == '3'){
                    $status_data = 'Resolved';
                }elseif($get_data->status == '4'){
                    $status_data = 'Cancelled';    
                }else{
                    $status_data = 'Opened';
                }

                $reply_data = TicketNotes::select('*')->where('ticket_id',$get_data->id)->where('is_deleted', '0')->first();

                if(isset($reply_data->description)){
                    $reply_html = '<div class="col-md-12 mt-2 mb-2">
                    <h5><b>Ticket Reply :</b></h5>
                    </div>
                    <div class="col-md-12">
                    <h6><b>Comments :</b></h6>
                    <p>'.$reply_data->description.'</p>
                    <p><b>Reply Date :</b> <i>'.$reply_data->created_at.'</i></p>
                </div>';
                }else{
                    $reply_html = '';
                }       

                $html_data = '<div class="row">
                <div class="col-md-12 mb-2">
                    <h4><b>Ticket Details :</b></h4>
                </div>
                <div class="col-md-4">
                <h6><b>Name :</b> '.$get_data->customer_name.'</h6>
                </div>
                <div class="col-md-4">
                <h6><b>Email :</b> '.$get_data->email.'</h6>
                </div>
                <div class="col-md-4">
                <h6><b>Phone No. :</b> '.$get_data->phone.'</h6>
                </div>
                <div class="col-md-4">
                <h6><b>Ticket Date :</b> '.$get_data->created_at.'</h6>
                </div>
                <div class="col-md-4">
                <h6><b>Status :</b> '.$status_data.'</h6>
                </div>
                <div class="col-md-12">
                    <h6><b>Comments :</b></h6>
                    <p>'.$get_data->description.'</p>
                </div>
                '.$reply_html.'
                </div>';

                return response()->json([
                    'flag' => true,
                    'html_data' => $html_data,
                    'message' => 'Reference number has been matched',
                ], 202);
            }else{
                return response()->json([
                    'flag' => false, 
                    'message' => 'Reference number did not match'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['flag' => false, 'message' => $e->getMessage()], 500);
        }
    }
}