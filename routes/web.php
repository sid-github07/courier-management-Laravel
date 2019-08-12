<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'FrontController@index')->name('front.index');
Route::get('aboutus', 'FrontController@aboutus')->name('front.aboutus');
Route::get('contactus', 'FrontController@contactus')->name('front.contactus');
Route::get('faq', 'FrontController@faq')->name('front.faq');
Route::post('visitor/message', 'FrontController@visitorMessage')->name('visitor.message');
Route::post('search/currier', 'FrontController@searchCurrier')->name('search.currier');

Route::group(['middleware' => 'CacheRemover'], function() {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['middleware' => 'CheckLogin'], function() {

            Route::get('/', function () {
                return view('admin/login');
            })->name('admin');

            Route::post('authenticate', 'LoginController@authenticate')->name('admin.authenticate');
        });

        Route::group(['middleware' => 'admin', 'namespace' => 'Admin'], function () {

            Route::post('logout', function () {
                Auth::guard('admin')->logout();
                return redirect('admin');
            });
             
            /*
             * Authorise route list
             */
            //change password route
            Route::get('changePassword', 'AdminController@changePassword')->name('changepassform');
            Route::put('changePassword', 'AdminController@updatePassword')->name('changepassssubmit');
            //profile route
            Route::get('profile', 'AdminController@profileView')->name('admin.profile');
            Route::put('profile', 'AdminController@updateProfile');
            //basic setting route list
            Route::get('basicSetting', 'GeneralSettingController@basicSetting')->name('admin.basicSetting');
            Route::put('basicSetting/{basicSetting}', 'GeneralSettingController@updateBasicSetting')->name('admin.basicSettingUpdate');
            //sms setting route list
            Route::get('smsSetting', 'GeneralSettingController@smsSetting')->name('admin.smsSetting');
            Route::put('smsSetting/{smsSetting}', 'GeneralSettingController@updateSmsSetting')->name('admin.smsSettingUpdate');
            //email setting route list
            Route::get('emailSetting', 'GeneralSettingController@emailSetting')->name('admin.emailSetting');
            Route::put('emailSetting/{emailSetting}', 'GeneralSettingController@updateEmailSetting')->name('admin.emailSettingUpdate');

            //courier unit info route list
            Route::resource('courier/unit', 'UnitController');
            //courier type info route list
            Route::resource('courier/type', 'CourierTypeController');

            //Branch Info
            Route::resource('branch', 'BranchController');

            //Branch Manager Info & staff view route 
            Route::resource('branchmanager', 'BranchManagerController');
            Route::post('branchmanager/changepassword', 'BranchManagerController@changePassword')->name('branchmanager.changepassword');
            Route::get('staff/{branch}', 'UserController@allUserList')->name('admin.branch.staff');

            //dashboard
            Route::get('dashboard', 'AdminController@dashboard')->name('admin.dashboard');

            //company income route
            Route::get('company/income', 'BranchManagerController@companyIncome')->name('admin.company.income');

            //branch wise company income route
            Route::get('branch/income/{branch}', 'BranchManagerController@branchIncome')->name('admin.branch.income');
            Route::get('branch/income/{branch}/{date}', 'BranchManagerController@dateWiseBranchIncome')->name('admin.branch.income.date');
            Route::get('staff/branch/income/{branch}/{staff}', 'BranchManagerController@staffWiseBranchIncome')->name('admin.branch.income.staff');

            //frontend setting route list
            Route::get('frontend/testimonial', 'FrontendSettingController@testimonial')->name('frontend.testimonial');
            Route::put('frontend/testimonial', 'FrontendSettingController@testimonialUpdate');
            Route::post('frontend/testimonial/store', 'FrontendSettingController@storeNewTestimonial')->name('frontend.storeTestimonial');
            Route::put('frontend/testimonial/{testimonial}', 'FrontendSettingController@updateNewTestimonial')->name('frontend.updateTestimonial');
            Route::delete('frontend/testimonial/{testimonial}/delete', 'FrontendSettingController@deleteTestimonial')->name('frontend.deleteTestimonial');
            Route::get('frontend/logoicon', 'FrontendSettingController@logoicon')->name('frontend.logoicon');
            Route::put('frontend/logoicon', 'FrontendSettingController@logoiconUpdate');
            Route::get('frontend/social', 'FrontendSettingController@social')->name('frontend.social');
            Route::post('frontend/social', 'FrontendSettingController@socialAdd');
            Route::put('frontend/social/{social}', 'FrontendSettingController@socialUpdate')->name('frontend.socialUpdate');
            Route::delete('frontend/social/{social}', 'FrontendSettingController@socialDestroy')->name('frontend.socialDestroy');
            Route::get('frontend/background', 'FrontendSettingController@background')->name('frontend.background');
            Route::put('frontend/background', 'FrontendSettingController@backgroundUpdate');
            Route::get('frontend/headertext', 'FrontendSettingController@headertextsetting')->name('frontend.headertext');
            Route::put('frontend/headertext/{setting}', 'FrontendSettingController@headertextsettingUpdate')->name('frontend.headertextupdate');
            Route::get('frontend/curriercount', 'FrontendSettingController@curriercount')->name('frontend.curriercount');
            Route::put('frontend/curriercount/{setting}', 'FrontendSettingController@curriercountUpdate')->name('frontend.curriercountupdate');
            Route::get('frontend/aboutus', 'FrontendSettingController@aboutus')->name('frontend.aboutus');
            Route::PUT('frontend/aboutus', 'FrontendSettingController@updateAboutUs');
            Route::get('frontend/contactus', 'FrontendSettingController@contactus')->name('frontend.contactus');
            Route::PUT('frontend/contactus', 'FrontendSettingController@updateContactus');
            Route::get('frontend/footer', 'FrontendSettingController@footer')->name('frontend.footer');
            Route::PUT('frontend/footer', 'FrontendSettingController@updateFooter');
            Route::get('frontend/searchcurrier', 'FrontendSettingController@searchcurrier')->name('frontend.searchcurrier');
            Route::put('frontend/searchcurrier', 'FrontendSettingController@searchcurrierUpdate');
            //faq crud
            Route::get('frontend/faq', 'FrontendSettingController@faq')->name('frontend.faq');
            Route::post('frontend/faq/store', 'FrontendSettingController@storeNewFaq')->name('frontend.storeFaq');
            Route::put('frontend/faq/{faq}', 'FrontendSettingController@updateNewFaq')->name('frontend.updateFaq');
            Route::delete('frontend/faq/{faq}/delete', 'FrontendSettingController@deleteFaq')->name('frontend.deleteFaq');

            //service section route list
            Route::get('frontend/services', 'FrontendSettingController@services')->name('frontend.services');
            Route::put('frontend/services', 'FrontendSettingController@servicesUpdate');
            Route::post('frontend/services/store', 'FrontendSettingController@storeNewServices')->name('frontend.storeServices');
            Route::put('frontend/services/{services}', 'FrontendSettingController@updateNewServices')->name('frontend.updateServices');
            Route::delete('frontend/services/{services}/delete', 'FrontendSettingController@deleteServices')->name('frontend.deleteServices');

            //visitor message list show
            Route::get('front/visitorMessage', 'FrontendSettingController@showVisitorMessage')->name('frontend.visitorMessage');
            Route::delete('front/visitorMessage/{message}', 'FrontendSettingController@deleteVisitorMessage')->name('frontend.deleteVisitorMessage');
        });
    });




    Auth::routes();

    // Authentication Routes...
    Route::get('manager', [
        'as' => 'login',
        'uses' => 'Auth\LoginController@showLoginForm'
    ]);
    Route::post('manager', [
        'as' => 'login',
        'uses' => 'Auth\LoginController@login'
    ]);



    Route::group(['namespace' => 'Manager'], function () {Route::post('password/reset', 'PasswordResetController@sendResetLink')->name('password.reset');
        Route::get('password/reset/{token}', 'PasswordResetController@resetLink')->name('password.token');
        Route::put('password/change', 'PasswordResetController@passwordReset')->name('password.change');        
    });

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['middleware' => 'manager', 'prefix' => 'manager', 'namespace' => 'Manager'], function () {

        //user profile & credentials routes
        Route::get('profile', 'ManagerController@profileView')->name('manager.profile');
        Route::put('profile', 'ManagerController@updateProfile');

        //all branch list route
        Route::get('branch/list', 'ManagerController@branchList')->name('manager.branchlist');

        //all branch staff route list
        Route::resource('branchstaff', 'BranchStaffController');
        Route::post('branchstaff/changepassword', 'BranchStaffController@changePassword')->name('branchstaff.changepassword');

        //Departure & Upcoming courier info route list
        Route::get('departure/currier', 'CurrierInfoController@departureBranchCurrierList')->name('departure.manager');
        Route::get('upcoming/currier', 'CurrierInfoController@upcomingBranchCurrierList')->name('upcoming.manager');
        Route::get('departure/invoice/{currierInfo}', 'CurrierInfoController@currierInvoice')->name('manager.departure.invoice');
        Route::get('upcoming/invoice/{currierInfo}', 'CurrierInfoController@upcomingCurrierInvoice')->name('manager.upcoming.invoice');

        //print slip route list
        Route::get('currier/slip/{id}', 'CurrierInfoController@printSlipView')->name('manager.currier.slip');

        //branch income route 
        Route::get('branch/income', 'CompanyPaymentController@branchWiseIncome')->name('manager.branch.income');
        Route::get('branch/income/date/{date}', 'CompanyPaymentController@dateWiseBranchIncome')->name('manager.branch.income.date');
        Route::get('branch/income/staff/{staff}', 'CompanyPaymentController@staffWiseBranchIncome')->name('manager.branch.income.staff');

        //change password route
        Route::get('changePassword', 'ManagerController@changePassword')->name('manager.changepassword');
        Route::put('changePassword', 'ManagerController@updatePassword');
    });


    Route::group(['prefix' => 'staff', 'namespace' => 'Staff'], function () {


            Route::get('/', function () {
                return view('staff/login');
            })->name('staff');
            Route::post('authenticate', 'LoginController@authenticate')->name('staff.authenticate');
            
            
            Route::get('password/request', 'PasswordResetController@showLinkRequestForm')->name('staff.password.request');
            Route::post('password/reset', 'PasswordResetController@sendResetLink')->name('staff.password.reset');
            Route::get('password/reset/{token}', 'PasswordResetController@resetLink')->name('staff.password.token');
            Route::put('password/change', 'PasswordResetController@passwordReset')->name('staff.password.change');
            
            
        Route::group(['middleware' => 'staff',], function () {

            Route::post('logout', function () {
                Auth::logout();
                return redirect('staff');
            })->name('staff.logout');
            //staff dashboard
            Route::get('dashboard', 'StaffController@dashboard')->name('staff.dashboard');

            //user profile & credentials routes
            Route::get('profile', 'StaffController@profileView')->name('staff.profile');
            Route::put('profile', 'StaffController@updateProfile');

            //branch list
            Route::get('branch/list', 'StaffController@branchList')->name('staff.branchlist');

            //courier info route list
            Route::resource('currier', 'CurrierInfoController');
            Route::get('currier/invoice/{currierInfo}', 'CurrierInfoController@currierInvoice')->name('currier.invoice');
            Route::put('currier/receive/staff', 'CurrierInfoController@receiveCurrier')->name('currier.receive');
            Route::put('currier/payment/staff', 'CurrierInfoController@paidCurrier')->name('currier.payment.staff');
            //print slip route list
            Route::get('currier/slip/{id}', 'CurrierInfoController@printSlipView')->name('staff.currier.slip');

            //search deliver currier
            Route::get('currier/deliver/search', 'CurrierInfoController@searchDeliverCurrier')->name('currier.deliver.search');
            Route::post('currier/deliver/search', 'CurrierInfoController@showDeliverCurrier');
            //send deliver notification
            Route::get('currier/deliver/notification', 'CurrierInfoController@notifyView')->name('currier.deliver.notify');
            Route::post('currier/deliver/notification', 'CurrierInfoController@findCurrier');
            Route::post('currier/deliiver/notification/send', 'CurrierInfoController@sendNotification')->name('send.notification.currier');
            //Cash Collection Route
            Route::get('cash/collection', 'CurrierInfoController@staffCasheCollection')->name('staff.cashe.collection');

            //change password route
            Route::get('changePassword', 'StaffController@changePassword')->name('staff.changepassword');
            Route::put('changePassword', 'StaffController@updatePassword');
            
        });
    });
});
