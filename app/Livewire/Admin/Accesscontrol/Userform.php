<?php

namespace App\Livewire\Admin\Accesscontrol;

use Livewire\Component;
use App\Models\User;

class Userform extends Component
{
    public $isSaving = false;
    public $showModal = true;
    public $user_id = null;
    public $username = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $isEdit = false;

    protected $listeners = [
        'edit-user' => 'loadUser',
        'closeModal' => 'closeModal',
    ];
    protected $rules = [
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string|in:admin,user',
    ];

    public function mount($userId = null)
    {
        if ($userId) {
            $this->loadUser($userId);
        }
    }

    public function loadUser($data){
        $user = User::findOrFail($data);
        $this->user_id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = 'user';
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function submit()
    {
        $this->isSaving = true;
        $this->validate();

        $this->isSaving = true;
        $existingUser = User::where('email', $this->email)->first();
        if ($this->isEdit) {
            if ($existingUser && $existingUser->id !== $this->user_id) {
                $this->addError('email', 'The email has already been taken by another user.');
                $this->isSaving = false;
                return;
            }
        } else {
            if ($existingUser) {
                $this->addError('email', 'The email has already been taken.');
                $this->isSaving = false;
                return;
            }
        }

        $roleId = 2;

        if ($this->isEdit) {
            $user = User::findOrFail($this->user_id);
            $user->update([
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role_id' => $roleId,
            ]);
            $message = 'User updated successfully.';
        } else {
            User::create([
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role_id' => $roleId,
            ]);
            $message = 'User created successfully.';
        }

        $this->dispatch('user-created', ['message' => $message]);
        $this->dispatch('userCreated', $message);
        $this->dispatch('closeModal');
        $this->resetForm();
        $this->isSaving = false;
    }
      public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->user_id = null;
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'user';
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.admin.accesscontrol.userform')
                ->layout('components.layouts.app', [
                'title' => 'Add User',
                'showLoader' => false,
            ]);
    }
}
