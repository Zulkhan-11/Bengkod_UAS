<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated(Request $request, $user) {
        $role = $user->role; 
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'dokter':
                return redirect()->route('dokter.dashboard'); 
            case 'pasien':
                return redirect()->route('pasien.dashboard');
            default:
                Auth::logout(); 
                return redirect('/login')->with('error', 'Anda tidak memiliki hak akses.');
        }
    }
    protected function loggedOut(Request $request)
    {
        return redirect('/');
    }
}