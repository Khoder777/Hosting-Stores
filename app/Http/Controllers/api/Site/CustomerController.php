<?php

namespace App\Http\Controllers\api\Site;

use App\Models\Customer;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SignupRegisterEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
class CustomerController extends Controller
{
    public function login(Request $request)
    {
        
        $vali=$request->validate([
            'email'=>'required|email',
            'password'=>'required|string|max:255|min:8',
        ]);
        $c=Customer::where('email' ,$request->email)->first();
        if($c)
        {
           
            if($c->status == 0)
            {
                return redirect()->back()->with('error','حسابك محظور حاليا ارسل رسالة تتضمن اسمك مع اعتذار للادمن');
            }
            if(!Hash::check($request->password,$c->password)){
              
                return redirect()->route('site.signin')->with('error','كلمة السر خاطئة');
            }
            return $this->successResponse($c, 'login ok');
            // if($c->verified_email==0)
            // {
            //     $otp=Str::random(6);
            //     $c->otp=$otp;
            //     $c->save();
            // Mail::to($c)->send(new SignupRegisterEmail($c));
            // $c->notify(new VerifyEmailNotification());
            // Cookie::queue('user_email',$c->email);
            // Cookie::queue('user_password',$request->password);
            
            // return view('site.auth.set_otp');
            // }
            // if(Auth::guard('customers')->attempt(['email'=>$c->email,'password'=>$request->password]))
            //     {
            //         Cookie::forget('user_email');
            //         Cookie::forget('user_password');
            //         return redirect()->route('site.home');
            //     }
            
        }

        return redirect()->back();
    }
    public function logout(Request $request)
    {
        Auth::guard('customers')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return redirect()->route('site.home');
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        $vali=$request->validate([
            'full_name'=>'required|string|max:255',
            'password'=>'required|string|max:255|min:8',
            'city_id'=>'required',
            'phone_number'=>'required|string|max:10',
            'email'=>'required|email|unique:customers,email',
        ]);
    
        $create=Customer::create([
            'full_name'=>$request->full_name,
            'password'=>Hash::make($request->password),
            'city_id'=>$request->city_id,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'image'=>'user_image.png',
            'otp'=>Str::random(6)
        ]);
        if($create)
        {
            // $create->notify(new VerifyEmailNotification());
            // Mail::to($create)->send(new SignupRegisterEmail($create));
            // Cookie::queue('user_email',$create->email);
            // Cookie::queue('user_password',$request->password);
            return view('site.auth.set_otp');
        }

        return redirect()->route('site.home');
    }

   public function setotp()
   {
    // $email=Cookie::get('user_email');
    // $password=Cookie::get('user_password');
    return view('site.auth.set_otp');
   }

   public function verifyEmail(Request $request)
   {
    $vali=$request->validate([
        'otp'=>'required|string|size:6',
    ]);
     $email=Cookie::get('user_email');
    $password=Cookie::get('user_password');
    $user=Customer::where('email',$email)->first();
    if($user)
    {
        if(Hash::check($password,$user->password))
        {
            if($request->otp==$user->otp)
            {
                $user->verified_email=1;
                $user->status=1;
                $user->otp=null;
                $user->save();
                if(Auth::guard('customers')->attempt(['email'=>$email,'password'=>$password]))
                {
                    Cookie::forget('user_email');
                    Cookie::forget('user_password');
                    return redirect()->route('site.home');
                }
                else return back();
            }
        }
    }
   }
}
