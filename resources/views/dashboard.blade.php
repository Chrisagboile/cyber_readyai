Route::get('/dashboard', function () {

    $role = auth()->user()->role?->slug;

    return match ($role) {

        'super-admin' => view('dashboard.super-admin'),

        'organisation-admin' => view('dashboard.organisation-admin'),

        'manager' => view('dashboard.manager'),

        'employee' => view('dashboard.employee'),

        default => abort(403, 'No valid role assigned.'),

    };

})->middleware(['auth', 'verified'])->name('dashboard');
