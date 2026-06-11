<?php

namespace App\Livewire\Pages\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $username = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $registerError = '';

    public function register()
    {
        $this->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $roleId = Role::where('role_name', 'user')->value('role_id');

        if (! $roleId) {
            $this->registerError = 'Unable to assign user role. Please seed roles first.';
            return;
        }

        $user = User::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $roleId,
            'status' => 'active',
        ]);

        Auth::login($user);
        session()->regenerate();

        return redirect()->route('user');
    }

    public function render()
    {
        return view('livewire.pages.auth.register')
            ->layout('components.layouts.auth', [
                'title' => 'Register',
                'showLoader' => false,
            ]);
    }
}
