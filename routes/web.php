<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Requests\Auth\LoginRequest;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ArticleController;
use Spatie\Permission\Contracts\Permission;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Middleware\EnsureProfileComplete;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Session\Middleware\AuthenticateSession;

Route::get('/', function () {
    $latestArticles = \App\Models\Article::with('user')
        ->where('status', 'approved')
        ->latest()
        ->take(6)
        ->get();

    $popularArticles = \App\Models\Article::with('user')
        ->where('status', 'approved')
        ->orderByDesc('hits')
        ->take(5)
        ->get();

    return Inertia::render('Welcome', [
        'articles' => $latestArticles,
        'popular'  => $popularArticles,
    ]);
})->name('home');


Route::get('dashboard', function () {
    return Inertia::render('Dashboard')->with('message', 'Selamat datang');
})->middleware(['auth', 'verified'])->name('dashboard'); /*, 'verified' <- bila ingin wajib verifikasi email */
Auth::routes(['verify' => true]); // untuk verifikasi email
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');
Route::get('/clear-message', function () {
    session()->forget('message');
    return redirect()->back();
});
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();
    $user = User::firstOrCreate(
        [
            'email' => $googleUser->getEmail(),
        ]

    );
    $user->assignRole('user');

    Auth::login($user);
    return redirect('/dashboard')->with("message", "Selamat datang");
});
Route::post('profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

// Roles and Permissions mulai disini
// users
Route::middleware(['auth', 'web', 'verified'])->group(function () {
    

Route::resource("users", UserController::class)
->only(['create','store'])
->middleware("permission:users.create");

Route::resource("users", UserController::class)
->only(['edit','update'])
->middleware("permission:users.edit");

Route::resource("users", UserController::class)
->only(['destroy'])
->middleware("permission:users.delete");

Route::put('/users/{user}/toggleStatus', [UserController::class, 'toggleStatus'])
    ->name('users.toggleStatus')
    ->middleware('permission:users.toggleStatus');


Route::resource("users", UserController::class)
->only(['index', 'show'])
->middleware("permission:users.create|users.edit|users.delete|users.view");

Route::resource("users", UserController::class)
->only(['show'])
->middleware("permission:users.show");

// roles
Route::resource("roles", RoleController::class)
->only(['create','store'])
->middleware("permission:roles.create");

Route::resource("roles", RoleController::class)
->only(['edit','update'])
->middleware("permission:roles.edit");

Route::resource("roles", RoleController::class)
->only(['destroy'])
->middleware("permission:roles.delete");

Route::resource("roles", RoleController::class)
->only(['index','show'])
->middleware("permission:roles.create|roles.edit|roles.delete|roles.view");

// permissions
Route::resource("permissions", PermissionController::class)
->only(['create','store'])
->middleware("permission:permissions.create");

Route::resource("permissions", PermissionController::class)
->only(['edit','update'])
->middleware("permission:permissions.edit");

Route::resource("permissions", PermissionController::class)
->only(['destroy'])
->middleware("permission:permissions.delete");

Route::resource("permissions", PermissionController::class)
->only(['index','show'])
->middleware("permission:permissions.create|permissions.edit|permissions.delete|permissions.view");

// Logs
Route::resource("logs", LogController::class)
->only(['index','show'])
->middleware("permission:logs.view");



Route::middleware(['auth'])->group(function () {
    // Moderation khusus admin/editor
    Route::get('/articles/moderation', [ArticleController::class, 'moderation'])
        ->name('articles.moderation')
        ->middleware('permission:articles.approve');

    //List Artikel yang udah di Approve
    Route::get('/articles/approved', [ArticleController::class, 'approved'])
        ->name('articles.approved')
        ->middleware('permission:articles.view');


    // Approve & Reject
    Route::post('/articles/{article}/approve', [ArticleController::class, 'approve'])
        ->name('articles.approve')
        ->middleware('permission:articles.approve');

    Route::post('/articles/{article}/reject', [ArticleController::class, 'reject'])
        ->name('articles.reject')
        ->middleware('permission:articles.reject');
    
        Route::delete('/articles/bulk-delete', [ArticleController::class, 'bulkDelete'])
        ->name('articles.bulkDelete')
        ->middleware('permission:articles.delete');

    Route::get('/articles/trashed', [ArticleController::class, 'trashed'])
        ->name('articles.trashed')
        ->middleware('permission:articles.view');

    Route::post('/articles/{id}/restore', [ArticleController::class, 'restore'])
        ->name('articles.restore')
        ->middleware('permission:articles.restore');

    Route::delete('/articles/{id}/force-delete', [ArticleController::class, 'forceDelete'])
        ->name('articles.forceDelete')
        ->middleware('permission:articles.forceDelete');

    // CRUD Articles tapi exclude show karena sudah pakai slug
    Route::resource('articles', ArticleController::class)
        ->except(['show'])
        ->middleware('permission:articles.view');

    // Route publik (akses artikel via slug)
    Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])
        ->name('articles.show');

});








});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
