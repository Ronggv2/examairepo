<?php

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Main;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\Joinexam;
use App\Livewire\Pages\Auth\Exam\Examform;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Pages\Userpages\Userdashboard;
use App\Livewire\Admin\Accesscontrol\User;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Pages\Userpages\Usermenu;
use App\Livewire\Pages\Auth\Exam\Examresult;
use App\Livewire\Admin\Ai\Ailog;

Route::get('/', Main::class);
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/joinexam', Joinexam::class)->name('joinexam');
Route::get('/exam', Examform::class)->name('examform');


Route::get('/result', Examresult::class)->name('examresult');


Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/users', User::class)->name('users');
    Route::get('/ailog', Ailog::class)->name('ailog');
});

Route::prefix('user')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/', Userdashboard::class)->name('user');
    Route::get('/questionform', Usermenu::class)->name('questionform');
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');


