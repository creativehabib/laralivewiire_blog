<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Livewire\Admin\Categories\CategoryForm;
use App\Livewire\Admin\Categories\CategoryTable;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Livewire\Admin\Posts\PostForm;
use App\Livewire\Admin\Posts\PostTable;
use App\Livewire\Admin\Tags\TagCreate;
use App\Livewire\Admin\Tags\TagEdit;
use App\Livewire\Admin\Tags\TagsIndex;
use App\Livewire\Frontend\Homepage;
use App\Livewire\Frontend\AuthorPage;
use App\Livewire\Frontend\CategoryPage;
use App\Livewire\Frontend\SinglePost;
use App\Livewire\Frontend\TagPage;
use App\Support\PermalinkManager;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', HomePage::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('media', 'media')
    ->middleware(['auth', 'verified'])
    ->name('media');

// --- নতুন সাইটম্যাপ রুট ---
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-posts-{year}-{month}.xml', [SitemapController::class, 'posts'])
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']) // YYYY-MM ফরম্যাট নিশ্চিত করা
    ->name('sitemap.posts');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
// --- সাইটম্যাপ রুট শেষ ---

// ২. এখন "Greedy" (ওয়াইল্ডকার্ড) ক্যাটাগরি রুট ডিফাইন করুন।
$categoryPrefixEnabled = general_settings('category_slug_prefix_enabled');
$categoryPrefixEnabled = is_null($categoryPrefixEnabled) || (bool)$categoryPrefixEnabled;
$categoryRouteUri = $categoryPrefixEnabled ? '/category/{category:slug}' : '/{category:slug}';

$categoryRoute = Route::get($categoryRouteUri, CategoryPage::class)
    ->name('categories.show');

Route::get('/author/{author}', AuthorPage::class)->name('authors.show');
Route::get('/tags/{slug}', TagPage::class)->name('tags.show');

$permalinkRoute = PermalinkManager::routeDefinition();


if (! $categoryPrefixEnabled && $permalinkRoute['template'] === '%postname%') {
    $categoryRoute->missing(function ( Request $request) {
        return redirect()->route('posts.show', ['post' => $request->route('category')]);
    });
}


    Route::middleware(['auth', 'preventBackHistory']) ->group(function () {
        Route::prefix('setting')->name('settings.')->group(function () {
            Route::controller(SettingController::class)->group(function(){
                Route::get('/permalinks', 'permalinksSetting')->name('permalinks')->middleware('permission:permalinks.view');
                Route::get('/general', 'generalSettings')->name('general')->middleware('permission:setting.view');
                Route::get('/cache-management', 'cacheManagement')->name('cacheManagement')->middleware('permission:setting.view');
                Route::get('/sitemap', 'sitemapSettings')->name('sitemap')->middleware('permission:setting.view');
            });
        });

        Route::prefix('blog')->name('blogs.')->group(function () {
            Route::middleware(['auth', 'preventBackHistory']) ->group(function () {
                Route::get('/categories', CategoryIndex::class)->name('categories.index')->middleware('permission:category.view');
                Route::get('/categories/table', CategoryTable::class)->name('categories.table')->middleware('permission:category.view');
                Route::get('/categories/create', CategoryForm::class)->name('categories.create')->middleware('permission:category.create');
                Route::get('/categories/{categoryId}/edit', CategoryForm::class)->name('categories.edit')->middleware('permission:category.edit');
                Route::get('/tags/index', TagsIndex::class)->name('tags.index')->middleware('permission:tags.view');
                Route::get('/tags/create', TagCreate::class)->name('tags.create')->middleware('permission:tags.create');
                Route::get('/tags/{tag}/edit', TagEdit::class)->name('tags.edit')->middleware('permission:tags.edit');
                Route::get('/posts/index', PostTable::class)->name('posts.index')->middleware('permission:post.view');
                Route::get('/posts/create', PostForm::class)->name('posts.create')->middleware('permission:post.create');
                Route::get('/posts/{post}/edit', PostForm::class)->name('posts.edit')->middleware('permission:post.edit');
            });
        });
    });


// Admin route (এটিও '/admin' প্রিফিক্স সহ একটি সুনির্দিষ্ট রুট, তাই এটি Greedy রুটের আগে বা পরে থাকতে পারে, কোনো সমস্যা নেই)
Route::prefix('admin')->name('admin.')->group(function () {
//        Route::middleware(['guest','preventBackHistory']) ->group(function () {
//            Route::controller(AuthController::class)->group(function(){
//                Route::get('/login', 'loginForm')->name('login');
//                Route::post('/login', 'login')->name('login.submit');
//                Route::get('/forgot-password', 'forgotForm')->name('forgot');
//                Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send.password.reset.link');
//                Route::get('/password/reset/{token}', 'resetForm')->name('reset.password.form');
//                Route::post('/password/reset', 'resetPassword')->name('reset.password.submit');
//            });
//        });

   Route::middleware(['auth', 'preventBackHistory']) ->group(function () {
        Route::controller(AdminController::class)->group(function(){
//                Route::get('/dashboard', 'dashboard')->name('dashboard');
//                Route::post('/logout', 'logout')->name('logout');
//                Route::get('/profile', 'profile')->name('profile');
//                Route::post('/update-profile', 'updateProfile')->name('update.profile');
            Route::get('/settings', 'generalSettings')->name('settings')->middleware('permission:setting.view');
            Route::get('/settings/sitemap', 'sitemapSettings')->name('settings.sitemap');
        });


//        Route::resource('posts', AdminPostController::class)->only(['index', 'create', 'edit']);
//            Route::get('media-library',MediaLibraryController::class) ->name('media-library.index')->middleware('permission:media.view');
        Route::resource('polls', AdminPollController::class)->only(['index', 'create', 'edit']);

        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserManagementController::class);

        Route::view('menus', 'backend.pages.menus.index')
            ->name('menus.index')
            ->middleware('permission:menu.view');

    });
});


// ৩. সবশেষে "Greedy" পোস্ট রুটটি ডিফাইন করুন।
$postRoute = Route::get($permalinkRoute['uri'], SinglePost::class)
    ->name('posts.show');

if (! empty($permalinkRoute['constraints'])) {
    $postRoute->where($permalinkRoute['constraints']);
}


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');




    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
