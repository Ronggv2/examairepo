<?php

namespace App\Livewire\Admin\Accesscontrol;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User as UserModel;

class User extends Component
{
    use WithPagination;

    public $query = '';
    public $selectedRole = 'user';

    public $selectedUser = null;
    public $selectedUserId = null;

    public $showUserForm = false;
    public $showUserDetail = false;
    public $showDeleteConfirm = false;

    public $userToDelete = null;
    public $activeUserId = null;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [];

    protected $listeners = [
        'userCreated' => 'refreshUsers',
        'closeModal' => 'handleCloseModal',
    ];

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedSelectedRole()
    {
        $this->resetPage();
    }

    public function refreshUsers()
    {
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        return UserModel::query()
            ->when($this->selectedRole, function ($query) {
                $query->whereHas('role', function ($q) {
                    $q->where('role_name', $this->selectedRole);
                });
            })
            ->where(function ($q) {
                $q->where('username', 'like', "%{$this->query}%")
                  ->orWhere('email', 'like', "%{$this->query}%");
            })
            ->latest()
            ->paginate(10);
    }

    public function openModal($userId = null)
    {
        $this->selectedUserId = $userId;
        $this->showUserForm = true;
    }

    public function handleCloseModal()
    {
        $this->showUserForm = false;
        $this->selectedUserId = null;
        $this->activeUserId = null;
    }

    public function viewUser($id)
    {
        $this->selectedUser = UserModel::findOrFail($id);
        $this->selectedUserId = $id;
        $this->activeUserId = $id;

        $this->showUserDetail = true;
    }

    public function confirmEditUser($id)
    {
        $this->openModal($id);
    }

    public function confirmDeleteUser($id)
    {
        $this->userToDelete = $id;
        $this->showDeleteConfirm = true;
    }

    public function actionUser($id)
    {
        $user = UserModel::findOrFail($id);

        if ($user->status === 'suspended') {
            $user->status = 'active';
            $message = 'User unsuspended successfully!';
        } elseif ($user->status === 'active') {
            $user->status = 'inactive';
            $message = 'User set to inactive successfully!';
        } else {
            $user->status = 'active';
            $message = 'User activated successfully!';
        }

        $user->save();

        $this->activeUserId = $user->status === 'active' ? $id : null;
        $this->resetPage();

        $this->dispatch('schedule-created', [
            'message' => $message
        ]);
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->userToDelete = null;
    }

    public function deleteUser()
    {
        $user = UserModel::find($this->userToDelete);

        if ($user) {
            $user->status = 'suspended';
            $user->save();
        }

        $this->activeUserId = null;
        $this->cancelDelete();
        $this->resetPage();

        $this->dispatch('schedule-created', [
            'message' => 'User suspended successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.accesscontrol.user', [
            'users' => $this->users,
        ])
          ->layout('components.layouts.app', [
                'title' => 'Admin Dashboard',
                'showLoader' => false,
            ]);
    }
}