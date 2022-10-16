<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public static function categoryList()
    {
        return Category::where('parent_id', '=', 0)->with('children')->get();// parent id si 0 olanları getir childları ile alt kategırileri ile beraber // yani sadece ana kategoriler
    }

    public static function getSetting()
    {
        return Setting::first();
    }

    public function index()
    {
        $setting = Setting::first();
        $slider = Product::select('id','title','image','price','slug')->limit(4)->get();
        #print_r($slider);
        #exit();
        $data = [
        'setting'=>$setting,
        'slider'=>$slider,
        'page'=>'home'
        ];

        return view('home.index', $data );// sadece anasayfada page değişkeni dolu gidiyo bu sayede bu değişkenin gittiği yerin anasayfa olduğunu anlıyoruz
    }

    public function product($id, $slug)
    {
        $data = Product::find($id);
        print_r($data);
        exit();

    }

    public function aboutus()
    {
        $setting = Setting::first();
        return view('home.about', ['setting'=>$setting]);
    }

    public function references()
    {
        $setting = Setting::first();
        return view('home.references', ['setting'=>$setting]);
    }

    public function contact()
    {
        $setting = Setting::first();
        return view('home.contact', ['setting'=>$setting]);
    }

    public function sendmessage(Request $request)
    {
        $data = new Message();
        $data->name = $request->input('name');
        $data->email = $request->input('email');
        $data->phone = $request->input('phone');
        $data->subject = $request->input('subject');
        $data->message = $request->input('message');
      
        $data->save();
        return redirect()->route('contact')->with('success', 'Mesajınız kaydedilmiştir, Teşekkür ederiz');
    }

    public function fag()
    {
        return view('home.about');
    }

    public function login()
    {
        return view('admin.login');
    }


    public function logincheck(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->intended('admin');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        else
        {
            return view('admin.login');
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function test($id, $name)
    {
        return view('home.test',['id'=>$id, 'name'=>$name]);// viewe yolladık
        /*
        echo "Id Number :", $id;
        echo "          Name:", $name;
        for ($i=1;$i<=$id;$i++){
            echo "<br> $i $name";
        }
        */
    }
}
