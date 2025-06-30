<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Jangan lupa import Carbon

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // Arahkan ke dashboard pasien setelah registrasi berhasil
    protected $redirectTo = '/pasien/dashboard'; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     * --- VALIDATOR DISESUAIKAN DENGAN FORM ANDA ---
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:15'],
            'nik' => ['required', 'string', 'digits:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * --- FUNGSI CREATE DIGABUNG DENGAN LOGIKA NO RM OTOMATIS ---
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // 1. Ambil tahun dan bulan saat ini (misal: 2506 untuk Juni 2025)
        $bulanTahun = Carbon::now()->format('ym');

        // 2. Cari nomor RM terakhir yang dibuat pada bulan ini
        $pasienTerakhir = User::where('role', 'pasien')
                                ->where('no_rm', 'LIKE', $bulanTahun . '-%')
                                ->orderBy('no_rm', 'desc')
                                ->first();

        // 3. Buat nomor urut baru
        if ($pasienTerakhir) {
            $nomorTerakhir = (int) substr($pasienTerakhir->no_rm, -3);
            $nomorBaru = $nomorTerakhir + 1;
        } else {
            $nomorBaru = 1;
        }

        // 4. Format Nomor RM baru (misal: 2506-001)
        $noRmBaru = $bulanTahun . '-' . str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);

        // 5. Buat user baru dengan menyertakan semua data dari form DAN Nomor RM otomatis
        return User::create([
            'name' => $data['name'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pasien', // Otomatis set role sebagai 'pasien'
            'no_rm' => $noRmBaru, 
        ]);
    }
}
