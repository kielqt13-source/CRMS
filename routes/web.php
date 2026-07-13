<?php

use App\Http\Controllers\HandwrittenRecognitionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->hasAdminPrivileges()) {
            return redirect('/admin');
        }
        return redirect('/user');
    }
    return redirect('/login');
});
Route::middleware('auth')->group(function () {

    Route::prefix('user')->group(function () {

        // Dashboard
        Route::get('/', function () {
            $user = auth()->user();
            $stats = [
                'total'      => $user->recognitions()->count(),
                'completed'  => $user->recognitions()->where('status', 'completed')->count(),
                'verified'   => $user->recognitions()->where('status', 'verified')->count(),
                'pending'    => $user->recognitions()->whereIn('status', ['pending', 'processing'])->count(),
                'failed'     => $user->recognitions()->where('status', 'failed')->count(),
                'today'      => $user->recognitions()->whereDate('created_at', today())->count(),
                'rejected'   => $user->recognitions()->where('status', 'rejected')->count(),
            ];
            $byType = $user->recognitions()
                ->selectRaw('document_type, count(*) as total')
                ->groupBy('document_type')
                ->pluck('total', 'document_type');
            $recent = $user->recognitions()->latest()->take(8)->get();
            return view('user.dashboard', compact('stats', 'byType', 'recent'));
        })->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Pages
        Route::get('/inference', [PageController::class, 'inference'])->name('inference');

        // Recognitions
        Route::resource('recognitions', HandwrittenRecognitionController::class)->only([
            'index', 'create', 'store', 'show',
        ]);
        Route::post('/recognitions/store/batch', [HandwrittenRecognitionController::class, 'storeBatch'])
            ->name('recognitions.store.batch');

        // Verification
        Route::get('/recognitions/{recognition}/verify', [HandwrittenRecognitionController::class, 'verify'])
            ->name('recognitions.verify');
        Route::post('/recognitions/{recognition}/verify', [HandwrittenRecognitionController::class, 'saveVerification'])
            ->name('recognitions.verify.save');
    });

    // Admin Routes (for admin and super_admin)
    Route::middleware('IsAdmin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/', function () {
            return view('admin.dashboard', [
                'totalUsers'          => User::count(),
                'totalRecognitions'   => Recognition::count(),
                'pendingRecognitions' => Recognition::whereIn('status', ['pending', 'processing'])->count(),
            ]);
        })->name('admin.dashboard');

        // Reports & Analytics
        Route::prefix('reports')->group(function () {
            Route::get('/statistics', function () {
                return view('admin.reports.statistics');
            })->name('admin.reports.statistics');
            Route::get('/accuracy', function () {
                return view('admin.reports.accuracy');
            })->name('admin.reports.accuracy');
            Route::get('/activity', function () {
                return view('admin.reports.activity');
            })->name('admin.reports.activity');
        });

        // Records Management
        Route::prefix('records')->group(function () {
            Route::get('/', function () {
                return view('admin.records.index');
            })->name('admin.records.index');
            Route::get('/search', function () {
                return view('admin.records.search');
            })->name('admin.records.search');
            Route::get('/advanced', function () {
                return view('admin.records.advanced');
            })->name('admin.records.advanced');
        });

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', function () {
                return view('admin.users.index');
            })->name('admin.users.index');
            Route::get('/permissions', function () {
                return view('admin.users.permissions');
            })->name('admin.users.permissions');
            Route::get('/activity', function () {
                return view('admin.users.activity');
            })->name('admin.users.activity');
        });

        // Audit Log
        Route::prefix('audit')->group(function () {
            Route::get('/', function () {
                return view('admin.audit.index');
            })->name('admin.audit.index');
            Route::get('/processing', function () {
                return view('admin.audit.processing');
            })->name('admin.audit.processing');
            Route::get('/user-actions', function () {
                return view('admin.audit.user-actions');
            })->name('admin.audit.user-actions');
        });
    });
});

require __DIR__.'/auth.php';
