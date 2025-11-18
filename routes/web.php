<?php

use Illuminate\Support\Facades\Route;

/* Guest Controllers */
use App\Http\Controllers\Guest\GuestHomeController;
use App\Http\Controllers\Guest\SchoolProfileController as GuestSchoolProfileController;
use App\Http\Controllers\Guest\HouseController as GuestHouseController;
use App\Http\Controllers\Guest\FacilityController as GuestFacilityController;
use App\Http\Controllers\Guest\HogwartsProphetController as GuestHogwartsProphetController;
use App\Http\Controllers\Guest\AchievementController as GuestAchievementController;
use App\Http\Controllers\Guest\UserAuthController;

/* Admin Controllers */
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FacilityCategoryController;
use App\Http\Controllers\Admin\FacilityPhotoController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\HogwartsProphetController as AdminHogwartsProphetController;
use App\Http\Controllers\Admin\HouseController;
use App\Http\Controllers\Admin\ProfessorController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\FounderController;
use App\Http\Controllers\Admin\CommentManagementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\StudentSummaryController;


/* ============================
   USER AUTHENTICATION
============================= */
Route::prefix('user')->name('user.')->group(function () {

    // Guest only: login/register
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');

        Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [UserAuthController::class, 'register'])->name('register.submit');
    });

    // Auth only: profile & logout
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [UserAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserAuthController::class, 'updateProfile'])->name('profile.update');

        Route::put('/password', [UserAuthController::class, 'changePassword'])->name('password.change');
    });

});


/* ============================
   PUBLIC (GUEST) PAGES
============================= */

Route::redirect('/', 'guest');

Route::prefix('guest')->name('guest.')->group(function () {

    // Home & school profile
    Route::get('/', [GuestHomeController::class, 'index'])->name('home');
    Route::get('/school-profile', [GuestSchoolProfileController::class, 'index'])->name('school-profiles');

    // Houses
    Route::get('/houses', [GuestHouseController::class, 'index'])->name('houses.index');
    Route::get('/houses/{house}', [GuestHouseController::class, 'show'])->name('houses.show');

    // Facilities list & detail
    Route::get('/facilities', [GuestFacilityController::class, 'index'])->name('facilities.index');
    Route::get('/facilities/highlight', [GuestFacilityController::class, 'highlight'])->name('facilities.highlight');
    Route::get('/facilities/{slug}', [GuestFacilityController::class, 'show'])->name('facilities.show');

    // Photo views
    Route::post('/facilities/photos/{photoId}/view', [GuestFacilityController::class, 'trackPhotoView'])
        ->name('facilities.photos.view');

    // GET dynamic endpoints
    Route::get('/facilities/photos/{photoId}/like-status', [GuestFacilityController::class, 'getLikeStatus'])
        ->name('facilities.photos.like-status');
    Route::get('/facilities/photos/{photoId}/comments', [GuestFacilityController::class, 'getComments'])
        ->name('facilities.photos.comments');

    // Actions: require login
    Route::middleware('auth')->group(function () {
        Route::post('/facilities/photos/{photoId}/like', [GuestFacilityController::class, 'toggleLike'])
            ->name('facilities.photos.like');
        Route::post('/facilities/photos/{photoId}/comments', [GuestFacilityController::class, 'storeComment'])
            ->name('facilities.photos.comments.store');
    });


    /* Hogwarts Prophet */
    Route::get('/hogwarts-prophet', [GuestHogwartsProphetController::class, 'index'])
        ->name('hogwarts-prophet.index');
    Route::get('/hogwarts-prophet/{slug}', [GuestHogwartsProphetController::class, 'show'])
        ->name('hogwarts-prophet.show');

    Route::get('/hogwarts-prophet/{articleId}/like-status', [GuestHogwartsProphetController::class, 'getLikeStatus'])
        ->name('hogwarts-prophet.like-status');
    Route::get('/hogwarts-prophet/{articleId}/comments', [GuestHogwartsProphetController::class, 'getComments'])
        ->name('hogwarts-prophet.comments');

    Route::post('/hogwarts-prophet/{articleId}/comments', [GuestHogwartsProphetController::class, 'storeComment'])
        ->middleware('auth')
        ->name('hogwarts-prophet.comments.store');

    Route::post('/hogwarts-prophet/{articleId}/like', [GuestHogwartsProphetController::class, 'toggleLike'])
        ->middleware('auth')
        ->name('hogwarts-prophet.like');


    /* Achievements */
    Route::get('/achievements', [GuestAchievementController::class, 'index'])->name('achievements.index');
    Route::get('/achievements/{id}', [GuestAchievementController::class, 'show'])->name('achievements.show');
    Route::post('/achievements/{achievementId}/view', [GuestAchievementController::class, 'trackView'])
        ->name('achievements.view');

    Route::get('/achievements/{achievementId}/like-status', [GuestAchievementController::class, 'getLikeStatus'])
        ->name('achievements.like-status');
    Route::get('/achievements/{achievementId}/comments', [GuestAchievementController::class, 'getComments'])
        ->name('achievements.comments');

    Route::post('/achievements/{achievementId}/like', [GuestAchievementController::class, 'toggleLike'])
        ->middleware('auth')
        ->name('achievements.like');

    Route::post('/achievements/{achievementId}/comments', [GuestAchievementController::class, 'storeComment'])
        ->middleware('auth')
        ->name('achievements.comments.store');

});



/* ============================
   ADMIN LOGIN
============================= */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


/* ============================
   ADMIN DASHBOARD (Protected)
============================= */
Route::middleware(['auth:admin', \App\Http\Middleware\PreventBackAfterLogout::class])
    ->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('hogwarts-prophet', AdminHogwartsProphetController::class);
    Route::patch('hogwarts-prophet/{id}/archive', [AdminHogwartsProphetController::class, 'archive'])
        ->name('hogwarts-prophet.archive');

    // Facilities admin
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [FacilityController::class, 'index'])->name('index');
        Route::get('/create', [FacilityController::class, 'create'])->name('create');
        Route::post('/', [FacilityController::class, 'store'])->name('store');

        Route::get('/{category}/edit', [FacilityController::class, 'edit'])->name('edit');
        Route::put('/{category}', [FacilityController::class, 'update'])->name('update');
        Route::delete('/{category}', [FacilityController::class, 'destroy'])->name('destroy');

        // Photos
        Route::prefix('categories/{category}/photos')->name('categories.photos.')->group(function () {
            Route::get('/create', [FacilityPhotoController::class, 'create'])->name('create');
            Route::post('/', [FacilityPhotoController::class, 'store'])->name('store');
            Route::get('/{photo}/edit', [FacilityPhotoController::class, 'edit'])->name('edit');
            Route::put('/{photo}', [FacilityPhotoController::class, 'update'])->name('update');
            Route::delete('/{photo}', [FacilityPhotoController::class, 'destroy'])->name('destroy');
        });
    });

    Route::resource('houses', HouseController::class)->only(['index', 'edit', 'update']);
    Route::post('houses/{house}/students', [HouseController::class, 'storeStudents'])->name('houses.storeStudents');
    Route::post('houses/{house}/achievements', [HouseController::class, 'storeAchievement'])->name('houses.storeAchievement');

    Route::get('/houses/{house}/summary/download', [StudentSummaryController::class, 'download'])
        ->name('houses.summary.download');

    Route::resource('professors', ProfessorController::class);
    Route::resource('achievements', AchievementController::class);
    Route::resource('students', StudentController::class);

    // School profile
    Route::prefix('school-profile')->name('school-profile.')->group(function () {
        Route::get('/', [SchoolProfileController::class, 'index'])->name('index');
        Route::get('/edit', [SchoolProfileController::class, 'edit'])->name('edit');
        Route::put('/', [SchoolProfileController::class, 'update'])->name('update');

        // Founders
        Route::resource('founders', FounderController::class);
    });

    // Comments management
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [CommentManagementController::class, 'index'])->name('index');
        Route::get('/facility-photos', [CommentManagementController::class, 'facilityComments'])->name('facility-photos');
        Route::get('/hogwarts-prophet', [CommentManagementController::class, 'prophetComments'])->name('hogwarts-prophet');
        Route::get('/achievements', [CommentManagementController::class, 'achievementComments'])->name('achievements');
        Route::get('/likes-stats', [CommentManagementController::class, 'likesStats'])->name('likes-stats');

        Route::delete('/facility-photos/{id}', [CommentManagementController::class, 'deleteFacilityComment'])->name('facility-photos.delete');
        Route::delete('/hogwarts-prophet/{id}', [CommentManagementController::class, 'deleteProphetComment'])->name('hogwarts-prophet.delete');
        Route::delete('/achievements/{id}', [CommentManagementController::class, 'deleteAchievementComment'])->name('achievements.delete');

        Route::post('/toggle-approval', [CommentManagementController::class, 'toggleApproval'])->name('toggle-approval');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::post('/reset', [SettingController::class, 'reset'])->name('reset');
    });

    // Users admin
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/ban', [UserManagementController::class, 'ban'])->name('ban');
        Route::post('/{id}/activate', [UserManagementController::class, 'activate'])->name('activate');
        Route::post('/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('reset-password');
    });

});


/* Redirect /login → /user/login */
Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');
