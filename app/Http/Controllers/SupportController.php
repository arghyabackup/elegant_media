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
use App\Models\Agents;

class SupportController extends Controller{
    public function index()
    {   
        if(Auth::check()){
            $tickets = Tickets::latest()->where('is_deleted', '0')->get();

            $data = [
                'tickets' => $tickets,
                'user_id' => auth()->user()->id
            ];
            return view('support_dashboard')->with($data);
        }
        return redirect("/support/login")->with('error','Opps! You do not have access');
    }
    public function view($id){
        $get_data = Tickets::select('*')->where('id',$id)->where('is_deleted', '0')->first();

        $reply_data = TicketNotes::select('*')->where('ticket_id',$id)->where('is_deleted', '0')->first();

        if($get_data->status == '2'){
            $status_data = 'Closed';
        }elseif($get_data->status == '3'){
            $status_data = 'Resolved';
        }elseif($get_data->status == '4'){
            $status_data = 'Cancelled';    
        }else{
            $status_data = 'Opened';
        }

        if($get_data->status == '0'){
            Tickets::where('id', '=', $id)->update(array('status' => '1'));
        }

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
        <div class="col-md-12">
        <h6><b>Name :</b> '.$get_data->customer_name.'</h6>
        <h6><b>Email :</b> '.$get_data->email.'</h6>
        <h6><b>Phone No. :</b> '.$get_data->phone.'</h6>
        <h6><b>Ticket Date :</b> '.$get_data->created_at.'</h6>
        <h6><b>Status :</b> '.$status_data.'</h6>
        </div>
        <div class="col-md-12">
            <h6><b>Comments :</b></h6>
            <p>'.$get_data->description.'</p>
        </div>
        '.$reply_html.'
        </div>';

        return $html_data;
    }
    public function reply(Request $request){
        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string', 'max:500']
        ]);
        $errors = $validator->errors();

        if($validator->fails()) {
            return response()->json(['flag' => false, 'message' => $errors->first()], 422);
        }
        try {
            $ticketData = TicketNotes::create([
                'ticket_id' => $request->input('ticket_id'),
                'agent_id' => $request->input('agent_id'),
                'description' => $request->input('description'),
            ]);
            
            Tickets::where('id', '=', $request->input('ticket_id'))->update(array('status' => '2'));

            // $full_ticket_data = TicketNotes::join('tickets', 'ticket_notes.ticket_id', '=', 'tickets.id')->where('tickets.id','=',$request->input('ticket_id'))->get(['tickets.email, tickets.reference_no, ticket_notes.description'])->first();

            // $mail_data = '<div class="row">
            //     <p><b>Comments :</b></p><br>
            //     <p>'.$full_ticket_data->description.'</p>
            //     </div>';

            // $details = [
            //     'title' => 'Ticket Support Reply #'.$full_ticket_data->reference_no,
            //     'body' => $mail_data
            // ];
            // Mail::to($full_ticket_data->email)->send(new \App\Mail\ticketSupportMail($details));

            return response()->json([
                'flag' => true,
                'message' => 'Ticket Reply successfully!',
            ], 202);
        } catch (\Exception $e) {
            return response()->json(['flag' => false, 'message' => $e->getMessage()], 422);
        }
    }
    public function registration()
    {
        return view('registration');
    }
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:6',
        ]);
           
        // $data = $request->all();
        // $check = $this->create($data);

        Agents::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
          ]); 
        return redirect("support")->withSuccess('Great! You have Successfully loggedin');
    }
    public function login()
    {
        return view('support_login');
    }
    public function post_login(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:100'],
        ]);
        $errors = $validator->errors();

        if($validator->fails()) {
            return response()->json(['flag' => false, 'message' => $errors->first()], 422);
        }
        try {
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'flag' => true,
                    'url'   => Route('support'),
                    'message' => 'Welcome to '.auth()->user()->name,
                ], 202);
            }else{
                return response()->json([
                    'flag' => false,
                    'message' => 'Incorrect Username and Password!!',
                ], 422);
            }
        }catch (\Exception $e) {
            return response()->json(['flag' => false, 'message' => $e->getMessage()], 422);
        }
    }
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('/support/login');
    }
}