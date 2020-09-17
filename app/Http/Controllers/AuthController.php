<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\User;
Use Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session;
 
class AuthController extends Controller
{
 
    private function otp($plainText, $cipherKey) 
    {      
      if (strlen($cipherKey) < strlen($plainText)) {
          return false;
      }

      $cipherText = '';
      for ($letter = 0; $letter < strlen($plainText); $letter++) {
          
          $plainTextCharacterAscii = ord($plainText[$letter]);
          $cipherKeyCharacterAscii = ord($cipherKey[$letter]);

          $plainTextCharacterInteger = $plainTextCharacterAscii - 65; // {0, 1, 2, ..., 25}
          $cipherKeyCharacterInteger = $cipherKeyCharacterAscii - 65; // {0, 1, 2, ..., 25}

          $oneTimePad = ($plainTextCharacterInteger + $cipherKeyCharacterInteger) % 26; // {0, 1, 2, ..., 25}

          $cipherTextCharacterAscii = $oneTimePad + 65;
          $cipherText .= $cipherTextCharacterAscii;
         
      }
      return ($cipherText);
     
    }

    public function index()
    {
      if(!Session::get('login')){
        return view('login');
      }
      else {
        return Redirect::to('dashboard');       
      }
    }
    
    public function index2()
    {
      if(!Session::get('login')){
        return view('login2');
      }
      else {
        return Redirect::to('dashboard');       
      }
    }
 
    public function registration()
    {
        return view('register');
    }
     
    public function postLogin(Request $request)
    {

        request()->validate([
          'email' => 'required',
          // 'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;
        $expire_date = $request->expire_date;

        $pjg_pswd = strlen($request->get('email'));
        $pswd_otp = self::otp($request->get('email'),random_bytes($pjg_pswd));

        date_default_timezone_set('Asia/Jakarta');
        $start = 'now';
        $exp = date('Y-m-d H:i',strtotime('+60 second',strtotime($start)));

        $data = User::where('email', $email)->where('is_verification', "1");
        if(count($data->get()) > 0){
            $data = $data->first();     

            $data->password = substr($pswd_otp,0,6);
            $data->expire_date = $exp;

            if($data->update()){
              Mail::send('password', ['id' => $data->id, 'name' => $data->name, 'password' => $data->password], function ($message) use ($request)
              {
                  $message->subject('secret');
                  $message->from('donotreply@xmail.com', 'Xmail');
                  $message->to($request->get('email'));
              });

              return Redirect::to("login2")->with('email', $data->email)->with('alert-success','Silahkan cek email untuk mendapatkan password.');;
            }                                           
        }
        else{
          return Redirect::to("login")->with('alert', 'Email salah atau akun belum diverifikasi!');
        }
    }

    public function postPassword(Request $request)
    {
      request()->validate([
        'email' => 'required',
        'password' => 'required',
      ]);

      date_default_timezone_set('Asia/Jakarta');
      $start = 'now';
      $exp = date('Y-m-d H:i',strtotime($start));

      $email = $request->email;
      $password = $request->password;                  

      $data = User::where('email', $email)->where('is_verification', "1");
      if(count($data->get()) > 0){
        $data = $data->first();        
        if($password == $data->password && $exp < $data->expire_date){
          Session::put('id', $data->id);
          Session::put('name', $data->name);       
          Session::put('email', $data->email);   
          // Session::put('level', $data->level);
          Session::put('login', TRUE);
          return redirect()->intended('dashboard');
        }
        else {
          return Redirect::to("login")->with('alert', 'Password salah atau Password kadaluarsa!');
        }    
      }
    }

    public function postRegistration(Request $request)
    {
      
      request()->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        // 'password' => 'required|min:6',
      ]);

      // $pjg_pswd = strlen($request->get('email'));

      $data =  new User();
      $data->name = $request->get('name');
      $data->email = $request->get('email');      
      // $data->password = self::otp($request->get('email'),Str::random($pjg_pswd));
      $data->is_verification = "0";

      if($data->save()){
          Mail::send('email', ['id' => $data->id, 'name' => $data->name], function ($message) use ($request)
          {
              $message->subject('Verifikasi Akun');
              $message->from('donotreply@xmail.com', 'Xmail');
              $message->to($request->get('email'));
          });
          
          return Redirect::to("registration")->with('alert-success','Berhasil daftar. Silahkan cek email untuk verifikasi.');
      }
      else{
          return redirect('registration')->with('alert', 'Gagal');
      }
    }

    public function verification($id)
    {
      $data =  User::where('id',$id)->first();
      $data->is_verification = "1";

      if($data->save()){
          return redirect('login')->with('alert-success', 'Berhasil verifikasi, silahkan login.');
      }
      else{
          return redirect('login')->with('alert', 'Gagal verifikasi');
      }
    }
 
    public function sendEmail(Request $request)
    {
      $data = User::where('email', $request->get('email'))->where('is_verification', '1');
      if(count($data->get()) > 0){
          $data = $data->first();
          Mail::send('email', ['id' => $data->id, 'name' => $data->name], function ($message) use ($request)
          {
              $message->subject('Verifikasi Akun');
              $message->from('donotreply@xmail.com', 'Xmail');
              $message->to($request->get('email'));
          });
          return redirect('registration')->with('alert-success','Silahkan cek email untuk verifikasi.');
      }
      else{
          return redirect('registration')->with('alert', 'Email belum terdaftar. Atau akun belum diverifikasi.');
      }
    }
     
    public function dashboard()
    {
      if(!Session::get('login')){
        return Redirect::to("login")->with('alert','Silahkan login terlebih dahulu.');
      }
      else {
        return view('dashboard');       
      }
    }
     
    public function logout() 
    {
        Session::flush();

        return Redirect('login');
    }    
}