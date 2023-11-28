<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Alert;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProvideCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return redirect()->back();
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        $userData = User::find($authUser->id);
        if($userData->is_active==0){
            toast('Silahkan hubungi admin untuk aktivasi akun','info');
            return redirect()->route('login');
        }else{
            Auth()->login($authUser, true);
            return redirect()->route('home');
        }
    }

    public function findOrCreateUser($socialUser, $provider)
    {
        // Get Social Account
        $socialAccount = SocialAccount::where('provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->first();
        // Jika sudah ada
        if ($socialAccount) {
            // return user
            return $socialAccount->user;

            // Jika belum ada
        } else {
            // User berdasarkan email
            $user = User::where('email', $socialUser->getEmail())->first();

            // Jika Tidak ada user
            if (!$user) {
                // Create user baru
                $user = User::create([
                    'name'  => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'level_user' => 2
                ]);
            }

            // Buat Social Account baru
            $user->socialAccounts()->create([
                'provider_id'   => $socialUser->getId(),
                'provider_name' => $provider
            ]);

            // return user
            return $user;
        }
    }
}
