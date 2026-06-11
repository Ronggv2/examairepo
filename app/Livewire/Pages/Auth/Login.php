<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $loginError = '';
    public function index()
    {
        return view('auth.login');
    }

   
public function login()
{
    $this->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = [
        'email' => $this->email,
        'password' => $this->password,
    ];

    if (Auth::attempt($credentials)) {

        session()->regenerate();

        // update last login
        auth()->user()->update([
            'last_login' => now()
        ]);

        // get role
        $role = auth()->user()->role->role_name;

        // redirect by role
        if ($role == 'admin') {

            return redirect()->route('admin.dashboard');

        } elseif ($role == 'user') {

                return redirect()->route('user');

        } else{

            return redirect()->route('guest');
        }

        // fallback
        return redirect('/');
    }

    $this->loginError = 'Invalid email or password.';
}

    public function render()
    {
        return view('livewire.pages.auth.login')
            ->layout('components.layouts.auth', [
                'title' => 'Login',
                'showLoader' => false,
            ]);
    }
}
