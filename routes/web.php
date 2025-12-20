<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Frontend\SitemapController;

use App\Livewire\Admin\Categories\CategoryForm;
use App\Livewire\Admin\Categories\CategoryTable;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Pages\PageForm;
use App\Livewire\Admin\Pages\PageTable;
use App\Livewire\Admin\Posts\PostForm;
use App\Livewire\Admin\Posts\PostTable;
use App\Livewire\Admin\Settings\CustomCssSettings;
use App\Livewire\Admin\Settings\CustomHtmlSettings;
use App\Livewire\Admin\Settings\CustomJsSettings;
use App\Livewire\Admin\Settings\HtaccessSettings;
use App\Livewire\Admin\Settings\RobotsTxt;
use App\Livewire\Admin\Settings\SettingsGenerator;
use App\Livewire\Admin\Settings\SitemapSettings;
use App\Livewire\Admin\Tags\TagCreate;
use App\Livewire\Admin\Tags\TagEdit;
use App\Livewire\Admin\Tags\TagsIndex;

use App\Livewire\Frontend\Homepage;
use App\Livewire\Frontend\AuthorPage;
use App\Livewire\Frontend\CategoryPage;
use App\Livewire\Frontend\PageShow;
use App\Livewire\Frontend\SinglePost;
use App\Livewire\Frontend\TagPage;

use App\Support\PermalinkManager;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', Homepage::class)->name('home');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::view('media', 'media')->middleware(['auth', 'verified'])->name('media');

/**
 * SITEMAPS
 */
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-posts-{year}-{month}.xml', [SitemapController::class, 'posts'])
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}'])
    ->name('sitemap.posts');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');

/**
 * STATIC FRONT ROUTES
 */
Route::get('/author/{author}', AuthorPage::class)->name('authors.show');
/**
 * TAG ROUTE (prefix depends on setting)
 */
$tagPrefixEnabled = PermalinkManager::tagPrefixEnabled();
$tagPrefix        = PermalinkManager::tagPrefix();

$tagUri = $tagPrefixEnabled ? "/{$tagPrefix}/{slug}" : "/{slug}";

$tagRoute = Route::get($tagUri, TagPage::class)->name('tags.show');

/**
 * Fallback: prefix OFF হলে /slug conflict হতে পারে (page/category/post)
 * তাই Tag fallback রাখা ভালো (optional)
 */
if (! $tagPrefixEnabled) {
    $tagRoute->missing(function (Request $request) {
        abort(404);
    });
}


/**
 * DYNAMIC FRONT ROUTES (Category + Page + Post)
 * NOTE: order matters.
 */
$permalinkRoute = PermalinkManager::routeDefinition();

/**
 * CATEGORY ROUTE
 */
$categoryPrefixEnabled = setting('category_slug_prefix_enabled');
$categoryPrefixEnabled = is_null($categoryPrefixEnabled) || (bool) $categoryPrefixEnabled;

$categoryUri = $categoryPrefixEnabled ? '/category/{category:slug}' : '/{category:slug}';
$categoryRoute = Route::get($categoryUri, CategoryPage::class)->name('categories.show');

/**
 * PAGE ROUTE (prefix depends on setting)
 */
$pagePrefixEnabled = PermalinkManager::pagePrefixEnabled();
$pagePrefix        = PermalinkManager::pagePrefix();

$pageUri = $pagePrefixEnabled ? "/{$pagePrefix}/{page:slug}" : "/{page:slug}";
$pageRoute = Route::get($pageUri, PageShow::class)->name('pages.show');

if (! $pagePrefixEnabled && $permalinkRoute['template'] === '%postname%') {
    $pageRoute->missing(function (Request $request) {
        return redirect()->route('posts.show', ['post' => $request->route('page')]);
    });
}
/**
 * IMPORTANT:
 * Post greedy route MUST be last
 */
$postRoute = Route::get($permalinkRoute['uri'], SinglePost::class)->name('posts.show');
if (! empty($permalinkRoute['constraints'])) {
    $postRoute->where($permalinkRoute['constraints']);
}

/**
 * OPTIONAL: if category prefix OFF and permalink is exactly %postname%
 * then /slug could be post too.
 * (Settings validation prevents worst-case, but keep this as safety)
 */
if (! $categoryPrefixEnabled && $permalinkRoute['template'] === '%postname%') {
    $categoryRoute->missing(function (Request $request) {
        return redirect()->route('posts.show', ['post' => $request->route('category')]);
    });
}

Route::get('/{key}.txt', function ($key) {
    $savedKey = setting('indexnow_key');
    if ($key === $savedKey) {
        return response($savedKey, 200)->header('Content-Type', 'text/plain');
    }
    abort(404);
})->where('key', '[a-zA-Z0-9]+');
/**
 * ADMIN / SETTINGS
 */
Route::middleware(['auth', 'preventBackHistory'])->group(function () {

    Route::prefix('setting')->name('settings.')->group(function () {
            Route::controller(SettingController::class)->group(function () {
                Route::get('/cache-management', 'cacheManagement')->name('cacheManagement')->middleware('permission:setting.view');
            });
            Route::get('/robots', RobotsTxt::class)->name('robots')->middleware('permission:setting.view');
            Route::get('/sitemap', SitemapSettings::class)->name('sitemap')->middleware('permission:setting.view');
            Route::get('/customs-css', CustomCssSettings::class)->name('custom-css')->middleware('permission:setting.view');
            Route::get('/custom-js', CustomJsSettings::class)->name('custom-js')->middleware('permission:setting.view');
            Route::get('/custom-html', CustomHtmlSettings::class)->name('custom-html')->middleware('permission:setting.view');
            Route::get('/htaccess', HtaccessSettings::class)->name('htaccess')->middleware('permission:setting.view');
        });

    Route::prefix('blog')->name('blogs.')->group(function () {
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

    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/pages/index', PageTable::class)->name('pages.index')->middleware('permission:page.view');
        Route::get('/pages/create', PageForm::class)->name('pages.create')->middleware('permission:page.create');
        Route::get('/pages/{page}/edit', PageForm::class)->name('pages.edit')->middleware('permission:page.edit');
    });
});

/**
 * Dynamic settings page
 */
Route::middleware(['auth', 'preventBackHistory'])
    ->get('/setting/{group}', SettingsGenerator::class)
    ->name('settings.dynamic')
    ->middleware('permission:setting.view');

Route::middleware(['auth','preventBackHistory'])
    ->prefix('setting')
    ->group(function () {
        Route::get('/export', [AdminController::class, 'export'])
            ->name('settings.export')
            ->middleware('permission:setting.view');

        Route::post('/import', [AdminController::class, 'import'])
            ->name('settings.import')
            ->middleware('permission:setting.edit');
    });

/**
 * Admin panel
 */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth', 'preventBackHistory'])->group(function () {
        Route::get('/setting/{group}', SettingsGenerator::class)->name('settings.dynamic')->middleware('permission:setting.view');
        Route::controller(AdminController::class)->group(function () {
            Route::get('/settings', 'generalSettings')->name('settings')->middleware('permission:setting.view');
        });

        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserManagementController::class);

        Route::view('menus', 'backend.pages.menus.index')->name('menus.index')->middleware('permission:menu.view');
    });
});

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
            )
        )
        ->name('two-factor.show');
});
