<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function login(Request $request)
    {
        // Validasi input
        $this->validateLogin($request);

        // Cek apakah pengguna aktif berdasarkan alamat email
        $user = $this->getUserByEmail($request->email);

        if (!$user) {
            return $this->sendFailedLoginResponse($request, 'Email tidak ditemukan');
        }

        // Pengecekan status akun
        if ($user->is_active == 0) {
            return $this->sendFailedLoginResponse($request, 'Akun anda belum aktif, silahkan kontak admin untuk aktivasi akun');
        }

        // Coba melakukan login
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // Jika login gagal
        return $this->sendFailedLoginResponse($request, 'Invalid credentials');
    }

    // Metode untuk mendapatkan pengguna berdasarkan alamat email
    protected function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    protected function sendFailedLoginResponse(Request $request, $message)
    {
        return redirect()->route('login')
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => $message,
            ]);
    }
}