<?php

use App\Http\Controllers\HandwrittenRecognitionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Models\Recognition;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::redirect('/dashboard', '/user');

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

    // Admin
    Route::get('/admin', function () {
        return view('admin.dashboard', [
            'totalUsers'          => User::count(),
            'totalRecognitions'   => Recognition::count(),
            'pendingRecognitions' => Recognition::whereIn('status', ['pending', 'processing'])->count(),
        ]);
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';
