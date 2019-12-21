<?php

Auth::routes();

Route::get('/welcome', function (){ return view('welcome'); })->name('welcome');
Route::get('/', 'HomeController@home');
Route::get('/changeLocale/{locale}', 'HomeController@changeLocale')->name('changeLocale');
Route::get('/changeBranch/{branch}', 'HomeController@changeBranch')->name('changeBranch');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('getAreas/{country}', 'SettingController@getAreas')->name('getAreas');
Route::get('getBranches/{vendor}', 'VendorBranchController@getBranches')->name('getBranches');

Route::group(['middleware' => ['auth', 'Branch']], function () {

    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/dashboard/charts', 'HomeController@charts')->name('charts');
    Route::get('profile', 'ProfileController@getProfile')->name('getProfile');
    Route::put('updateProfile/{user}', 'ProfileController@updateProfile')->name('updateProfile');
    Route::get('notApproved', 'HomeController@notApproved')->name('notApproved');
    Route::get('chooseBranch', 'HomeController@chooseBranch')->name('chooseBranch');


    /* =====     Admin MiddleWare     ===== */

    Route::group(['middleware' => ['Admin']], function () {
        Route::get('/translation', 'HomeController@translation')->name('translations');
        Route::post('updateLanguage', 'HomeController@updateLanguage')->name('updateLanguage');
        Route::resource('administrators', 'AdministratorController', ['except' => 'show']);
        // Route::resource('vendors', 'VendorController', ['except' => 'index', 'show']);
        Route::get('vendors/create', 'VendorController@create')->name('vendors.create');
        Route::post('vendors', 'VendorController@store')->name('vendors.store');
        Route::delete('vendors/{vendor}', 'VendorController@destroy')->name('vendors.destroy');

        Route::resource('settings', 'SettingController', ['except' => 'show']);
        Route::post('settings/createAjax', 'SettingController@createAjax')->name('createAjax');
        Route::post('settings/updateAjax', 'SettingController@updateAjax')->name('updateAjax');
        Route::post('settings/removeAjax', 'SettingController@removeAjax')->name('removeAjax');
        Route::get('settings/areas', 'SettingController@areas')->name('areas');

        Route::post('settings/areas/countries/store', 'SettingController@storeCountry')->name('areas.country.store');
        Route::post('settings/areas/countries/update', 'SettingController@updateCountry')->name('areas.country.update');
        Route::delete('settings/areas/countries/destroy/{country}', 'SettingController@destroyCountry')->name('areas.country.destroy');

        Route::post('settings/areas/store', 'SettingController@storeArea')->name('areas.store');
        Route::post('settings/areas/update', 'SettingController@updateArea')->name('areas.update');
        Route::delete('settings/areas/destroy/{area}', 'SettingController@destroyArea')->name('areas.destroy');

        Route::group(['prefix' => 'vendors/{vendor}'], function () {
            Route::get('/showLog', 'VendorController@showVendorsLog')->name('vendors.showVendorsLog');
            Route::get('/subscription', 'VendorController@subscription')->name('vendors.subscription');
            Route::put('/updateSubscription/{subscription?}', 'VendorController@updateSubscription')->name('vendors.updateSubscription');
            Route::get('/approve', 'VendorController@approve')->name('vendors.approve');
        });
    });

    Route::resource('drivers', 'DriverController', ['except' => 'index', 'show']);
    Route::get('drivers/{driver}/show', 'DriverController@index')->name('drivers.index');
    Route::get('drivers/{driver}/showLog', 'DriverController@showDriversLog')->name('drivers.showDriversLog');
    Route::get('drivers/{driver}/jobHistory/{filter?}', 'DriverController@jobHistory')->name('drivers.jobHistory');
    Route::get('drivers/{driver}/changePassword', 'DriverController@changePassword')->name('drivers.changePassword');
    Route::put('drivers/{driver}/updatePassword', 'DriverController@updatePassword')->name('drivers.updatePassword');
    Route::get('drivers/{driver}/approve', 'DriverController@approve')->name('drivers.approve');


    /* =====     Vendor MiddleWare     ===== */

    Route::group(['middleware' => ['Vendor']], function () {
        Route::get('vendors/{filter}/show', 'VendorController@index')->name('vendors.index');

        Route::group(['prefix' => 'vendors/{vendor}'], function () {
            Route::get('edit', 'VendorController@edit')->name('vendors.edit');
            Route::put('', 'VendorController@update')->name('vendors.update');
            Route::get('/changePassword', 'VendorController@changePassword')->name('vendors.changePassword');
            Route::put('/updatePassword', 'VendorController@updatePassword')->name('vendors.updatePassword');

            Route::group(['prefix' => '/accounts'], function () {
                Route::get('/', 'VendorAccountController@index')->name('vendors.accounts');
                Route::get('/create', 'VendorAccountController@create')->name('vendors.accounts.create');
                Route::post('/store', 'VendorAccountController@store')->name('vendors.accounts.store');
                Route::get('/{account}/edit', 'VendorAccountController@edit')->name('vendors.accounts.edit');
                Route::put('/{account}', 'VendorAccountController@update')->name('vendors.accounts.update');
                Route::delete('/{account}', 'VendorAccountController@destroy')->name('vendors.accounts.destroy');
                Route::get('/{account}/changePassword', 'VendorAccountController@changePassword')->name('vendors.accounts.changePassword');
                Route::put('/{account}/updatePassword', 'VendorAccountController@updatePassword')->name('vendors.accounts.updatePassword');
                Route::get('/{account}/assignBranches', 'VendorAccountController@getAssignBranches')->name('vendors.accounts.assignBranches');
                Route::post('/assignBranches', 'VendorAccountController@postAssignBranches')->name('vendors.accounts.branches.assign');
                Route::post('/revokeBranches', 'VendorAccountController@postRevokeBranches')->name('vendors.accounts.branches.revoke');
            });

            Route::group(['prefix' => '/branches'], function () {
                Route::get('/', 'VendorBranchController@index')->name('vendors.branches');
                Route::get('/create', 'VendorBranchController@create')->name('vendors.branches.create');
                Route::post('/store', 'VendorBranchController@store')->name('vendors.branches.store');
                Route::get('/{branch}/edit', 'VendorBranchController@edit')->name('vendors.branches.edit');
                Route::put('/{branch}', 'VendorBranchController@update')->name('vendors.branches.update');
                Route::delete('/{branch}', 'VendorBranchController@destroy')->name('vendors.branches.destroy');
            });
        });

        /* =====     jobs routes     ===== */
        Route::get('jobs/show/{job?}/{date?}/{fromDate?}/{toDate?}/{vendor?}/{branch?}', 'JobController@allJobs')->name('jobs.index');
        Route::get('liveJobs/', 'JobController@liveJobs')->name('jobs.liveJobs');
        Route::get('jobs/map', 'JobController@map')->name('jobs.map');
        Route::get('jobs/create/{type}', 'JobController@create')->name('jobs.create');
        Route::post('jobs/store', 'JobController@store')->name('jobs.store');
        Route::get('jobs/{job}/edit', 'JobController@edit')->name('jobs.edit');
        Route::put('jobs/{job}', 'JobController@update')->name('jobs.update');
        Route::delete('jobs/{job}', 'JobController@destroy')->name('jobs.destroy');
        Route::post('jobs/assignDriver', 'JobController@assignDriver')->name('jobs.assignDriver');
        Route::post('jobs/notifyDriversApi/{job}', 'JobController@notifyDriversApi')->name('jobs.notifyDriversApi');
        Route::post('jobs/notifySingleDriver', 'JobController@notifySingleDriver')->name('jobs.notifySingleDriver');
        Route::get('jobs/{job}/history', 'JobController@jobHistory')->name('jobs.history');
        Route::get('jobs/{job}/notifications', 'JobController@jobNotifications')->name('jobs.notifications');

        // Map Routes
        Route::get('getJobs', 'JobController@getJobs')->name('getJobs');
        Route::get('getOnlineDrivers', 'JobController@getOnlineDrivers')->name('getOnlineDrivers');

        // Reports
        Route::get('reports', 'ReportController@index')->name('reports');
        Route::get('getReport', 'ReportController@getReport')->name('getReport');
        Route::get('reports/jobs/export/{fromDate}/{toDate}', 'ReportController@exportJobs')->name('jobs.export');
        Route::get('reports/vendors/export/', 'ReportController@exportVendors')->name('vendors.export');
        Route::get('reports/vendors/deliveryFees/{fromDate}/{toDate}', 'ReportController@exportDeliveryFees')->name('vendors.deliveryFees');
        Route::get('reports/drivers/export', 'ReportController@exportDrivers')->name('drivers.export');
        Route::get('reports/drivers/jobs/export/{fromDate}/{toDate}/{driver}', 'ReportController@exportDriverJobs')->name('drivers.jobs.export');

    });
});
