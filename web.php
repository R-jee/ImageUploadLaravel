<?php

use App\Http\Controllers\BiolinkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\WebScraperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\EditApplicationFormController;
use App\Http\Controllers\CompanyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
// Route::any('/logout', [LogoutController::class, 'store'])->name('logout.perform');

Route::get('/fr-application-form/application-form/{slug}/page-id/{page_id}/tenant-id/{tenant_id}', [ApplicationFormController::class, 'index'])->name('app.form');
Route::post('fr-application-form/formsubmit', [ApplicationFormController::class, 'frApplicationSubmit'])->name('app.form.submit');
// Bio Link
//Route::get('/biolink', function () {
//    return view('biolink');
//});
//Route::get('/biolink',  [Controller::class, 'viewBiolink'])->name('biolink');
Route::get('/all_reviews/biolink/{biolinkID}/tenant/{tenant_id}', [Controller::class, 'viewAllReview'])->name('view_all_reviews');
Route::get('/all_categories/biolink/{biolinkID}/tenant/{tenant_id}', [Controller::class, 'viewAllCategoryWithMenu'])->name('view_all_categories');
Route::get('/all_stores/{biolinkID}/tenant/{tenant_id}', [Controller::class, 'viewAllRegionWithStores'])->name('view_all_stores');
Route::post('/review_sort', [ReviewController::class, 'updateOrder'])->name('review_sort');

Route::middleware([
    'web',
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(callback: function () {

    Route::get('/context/switchcompany/{id}', [Controller::class, 'switchContext'])->name('context.switchcompany');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Start of new store applications at super admin and franchise panel side related routes.
    Route::group(['prefix' => 'applications'], function () {
        Route::get('/', [ApplicationFormController::class, 'showApplicationsList'])->name('applications.list');
        Route::get('/delete/{id}', [ApplicationFormController::class, 'deleteApplication'])->name('application.delete');

        Route::post('/filter', [ApplicationFormController::class, 'filterRecords'])->name('applications.list.filter');
        Route::get('/export-list', [ApplicationFormController::class, 'exportLists'])->name('applications.export');
        // Email and Sms
        Route::post('/sms-status', [EditApplicationFormController::class, 'smsStatus'])->name('applications.sms.status');
        Route::post('/email-status', [EditApplicationFormController::class, 'emailStatus'])->name('applications.email.status');
        Route::get('/email', [EditApplicationFormController::class, 'emailView'])->name('applications.email');
        Route::get('/sms', [EditApplicationFormController::class, 'smsView'])->name('applications.sms');
        Route::post('/sms-saved', [EditApplicationFormController::class, 'smsForm'])->name('applications.sms.form');
        Route::post('/email-saved', [EditApplicationFormController::class, 'emailForm'])->name('applications.email.form');
        Route::get('/edit/{id}', [EditApplicationFormController::class, 'index'])->name('application.edit');
    });
   // Route::get('/application/edit/{id}', [EditApplicationFormController::class, 'index'])->name('application.edit');
    Route::post('/application/update/{id}', [EditApplicationFormController::class, 'updateApplicationForm'])->name('application.update');
    // End of new store applications related at super admin and franchise panel side routes

    Route::resource('reviews', ReviewController::class);
    Route::get('/scrape/scrapper/encodeurl', function (Request $request) {
        $WebScraperController = new WebScraperController();
        return $WebScraperController->iframeScraeUrl___runScript($request['url']);
    });

    Route::get('/event', function () {
        return view('biolink/event/event');
    })->name('event');
    Route::get('/banner', function () {
        return view('biolink/banner/banner');
    })->name('banner');

    Route::get('/design', [DesignController::class, 'index'])->name('design');
    Route::post('/design', [DesignController::class, 'updateBiolink'])->name('design.update.biolink');
    Route::post('/design/banner/update', [DesignController::class, 'updateBiolinkBanner'])->name('design.update.biolink.banner');
    Route::post('/design/events/update', [DesignController::class, 'updateBiolinkEvents'])->name('design.update.biolink.events');
    Route::post('/design/menus/update', [DesignController::class, 'updateBiolinkMenus'])->name('design.update.biolink.menus');
    Route::post('/design/reviews/update', [DesignController::class, 'updateBiolinkReviews'])->name('design.update.biolink.reviews');
    Route::post('/design/stores/update', [DesignController::class, 'updateBiolinkStores'])->name('design.update.biolink.stores');
    Route::post('/design/new-stores/update', [DesignController::class, 'updateBiolinkNewStores'])->name('design.update.biolink.newstores');

    Route::post('/update-sns-links-order', [DesignController::class, 'snsOrderUpdate'])->name('design.sns.order.update');

    Route::group(['prefix' => 'company'], function () {
        Route::get('/', [CompanyController::class, 'index'])->name('company.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('company.create');
        Route::post('/create', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/show/{tenant_id}', [CompanyController::class, 'show'])->name('company.show');
        Route::get('/edit/{tenant_id}', [CompanyController::class, 'edit'])->name('company.edit');
        Route::post('/update/{tenant_id}', [CompanyController::class, 'update'])->name('company.update');
        Route::get('/delete/{tenant_id}', [CompanyController::class, 'destroy'])->name('company.destroy');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
        Route::get('/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/create', [UsersController::class, 'store'])->name('users.store');
        Route::get('/{user}/show', [UsersController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::patch('/{user}/update', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/{user}/delete', [UsersController::class, 'destroy'])->name('users.destroy');
    });

    Route::group(['prefix' => 'menu'], function () {
        Route::get('/', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('/create', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('menu.edit');
        Route::post('/update/{menu}', [MenuController::class, 'update'])->name('menu.update');
        Route::get('/delete/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/create', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/{category}/show', [CategoryController::class, 'show'])->name('category.show');
        Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::get('/delete/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
        Route::get('/disable/{category}', [CategoryController::class, 'disableCategory'])->name('category.disable');
        Route::get('/enable/{category}', [CategoryController::class, 'enableCategory'])->name('category.enable');
    });
    Route::resource('events', EventController::class);
    Route::post('/event_sort', [EventController::class, 'updateOrder'])->name('event.sort');
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);

    Route::get('/reviews_preview', [ReviewController::class, 'preview'])->name('reviews.preview');

    // Route for stores
    Route::resource('stores', StoreController::class)->names([
        'create' => 'stores.create',
        'store' => 'stores.store',
        'show' => 'stores.show',
        'edit' => 'stores.edit',
        'update' => 'stores.update',
        'destroy' => 'stores.destroy'
    ]);
    Route::get('/stores_preview', [StoreController::class, 'preview'])->name('stores.preview');

    // Drag drop sorting for store regions
    Route::post('/store_region_sort', [StoreController::class, 'updateStoreRegionOrder'])->name('store_region_sort');
    Route::post('/get_store_sorting', [StoreController::class, 'getRegionSortOrder'])->name('get_store_sorting');
    Route::post('/get_menu_sorting', [CategoryController::class, 'getMenuSortOrder'])->name('get_menu_sorting');
    Route::post('/get_menu_edit_sorting', [CategoryController::class, 'getMenuEditSortOrder'])->name('get_menu_edit_sorting');
    Route::post('/get_edit_store_sorting', [StoreController::class, 'getRegionEditSortOrder'])->name('get_edit_store_sorting');
    // Route for Enable/Disable Store Region
    Route::get('/store_region_en/{store_id}', [StoreController::class, 'enableStoreRegion'])->name('store_region_en');
    Route::get('/store_region_dis/{store_id}', [StoreController::class, 'disableStoreRegion'])->name('store_region_dis');
    Route::post('/category_menu_sort', [CategoryController::class, 'updateCategoryMenuSortingOrder'])->name('category_menu_sort');
    Route::post('/category_sort', [CategoryController::class, 'updateCategorySortingOrder'])->name('category_sort');
    //Route for store sorting
    Route::post('/store_sorting_order', [StoreController::class, 'updateStoreOrder'])->name('store_sorting_order');

    Route::resource('usersettings', UserSettingsController::class)->names([
        'create' => 'usersettings.create',
        'store' => 'usersettings.store',
        'show' => 'usersettings.show',
        'edit' => 'usersettings.edit',
        'update' => 'usersettings.update',
        'destroy' => 'usersettings.destroy'
    ]);

    // Route for get category and items for preview
    Route::get('/get_categories', [CategoryController::class, 'getMenuDataPreview'])->name('get_categories');

    // Route for get store region and stores for preview
    Route::get('/get_stores', [StoreController::class, 'getStoreDataPreview'])->name('get_stores');

    // Route for get category items for preview frontend
    Route::get('/view_category_items/category/{category_id}/tenant/{tenant_id}', [BiolinkController::class, 'viewMenus'])->name('view_category_items');

    // Route for get Event items for preview
    Route::get('/get_events', [EventController::class, 'getEventDataPreview'])->name('get_events');

    // Route for get company information
    Route::get('/my_info', [CompanyController::class, 'myInformation'])->name('my_info');
    // Route for return view for company verification form
    Route::get('/verification_email', [CompanyController::class, 'emailVerificationForm'])->name('verification_email');
    // Route for send code
    Route::post('/send/code', function (Request $request) {
        $emailCodeSend = new CompanyController();
        return $emailCodeSend->sendEmailCode($request['email']);
    });
    Route::post('/verify/code', function (Request $request) {
        $verifyEmailCode = new CompanyController();
        $data['email'] = $request['email'];
        $data['emailCode'] = $request['emailCode'];
        return $verifyEmailCode->verifyEmailCode($data);
    });

    // Route for privacy page
    Route::get('/privacy_policy/tenant/{tenant_id}', [CompanyController::class, 'privacyPolicy'])->name('privacy_policy');
    Route::get('/edit_privacy_policy/edit/tenant/{tenant_id}', [CompanyController::class, 'editPrivacyPolicy'])->name('edit_privacy_policy');
    Route::post('/update_privacy_policy/edit/tenant/{tenant_id}', [CompanyController::class, 'updatePrivacyPolicy'])->name('update_privacy_policy');
    // Route for term of use page
    Route::get('/term_use/tenant/{tenant_id}', [CompanyController::class, 'termUse'])->name('term_use');
    Route::get('/edit_term_use/edit/tenant/{tenant_id}', [CompanyController::class, 'editTermUse'])->name('edit_term_use');
    Route::post('/update_term_policy/edit/tenant/{tenant_id}', [CompanyController::class, 'updateTermUse'])->name('update_term_use');

});

// Route for detail page
Route::get('/view_details/dataId/{dataID}/data/{data}/biolink/{biolinkID}/tenant/{tenant_id}', [Controller::class, 'viewDetailPage'])->name('view_details');
// Company Privacy Policy And Terms Of Use
Route::get('/company_privacy_policy/tenant/{tenant_id}', [Controller::class, 'companyPrivacyPolicy'])->name('company_privacy_policy');
Route::get('/company_terms_of_use/tenant/{tenant_id}', [Controller::class, 'companyTermsOfUse'])->name('company_terms_of_use');
