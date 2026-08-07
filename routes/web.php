<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDraftController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\StorekeeperController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfitLossController;
use App\Models\Medicine;




/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/debug-medicines', function () {
    return Medicine::select(
        'id',
        'name',
        'quantity',
        'expiry_date',
        'category_id'
    )->get();
});





/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

  Route::get('/dashboard', [DashboardController::class,'index'])
        ->middleware('role:admin')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Cashier
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cashier')->group(function () {

        Route::get('/cashier', [CashierController::class,'index'])
            ->middleware(['auth', 'role:cashier'])
            ->name('cashier.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | Pharmacist
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:pharmacist')->group(function () {

        Route::get('/pharmacist', [PharmacistController::class,'index'])
            ->name('pharmacist.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | Storekeeper
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:storekeeper')->group(function () {

        Route::get('/storekeeper', [StorekeeperController::class,'index'])
            ->name('storekeeper.dashboard');

    });

        /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class,'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class,'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class,'destroy'])
        ->name('profile.destroy');
    
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

        
     Route::middleware('role:admin')->group(function () {

        /*
    |--------------------------------------------------------------------------
    | Users Route
    |--------------------------------------------------------------------------
    */
   Route::resource('users', UserController::class)
    ->middleware('role:admin');

    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
    ->name('users.toggleStatus')
    ->middleware('role:admin');

    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
    ->name('users.resetPassword');
    });

      Route::middleware('role:admin,pharmacist')->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
    
        Route::get('/categories', [CategoryController::class,'index'])
            ->name('categories.index');
    
        Route::post('/categories', [CategoryController::class,'store'])
            ->name('categories.store');
    
        Route::get('/categories/{id}/edit', [CategoryController::class,'edit'])
            ->name('categories.edit');
    
        Route::put('/categories/{id}', [CategoryController::class,'update'])
            ->name('categories.update');
    
        Route::delete('/categories/{id}', [CategoryController::class,'destroy'])
            ->name('categories.destroy');
    

    });   
    
      Route::middleware('role:admin,pharmacist,storekeeper')->group(function () {

        /*
    |--------------------------------------------------------------------------
    | Medicines
    |--------------------------------------------------------------------------
    */


    Route::post('/medicines', [MedicineController::class,'store'])
        ->name('medicine.store');
        

    Route::get('/medicines/{id}/edit', [MedicineController::class,'edit'])
        ->name('medicine.edit');
       

    Route::put('/medicines/{id}', [MedicineController::class,'update'])
        ->name('medicine.update');
       

    Route::delete('/medicines/{id}', [MedicineController::class,'destroy'])
        ->name('medicine.destroy');

    Route::get('/medicines/barcode/{barcode}', [MedicineController::class, 'barcode']);
       

    
    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */

    Route::resource('inventory', InventoryController::class)
        ->except(['show']);


    Route::get(
        '/inventory/ledger',
        [InventoryController::class,'ledger']
    )->name('inventory.ledger');





    });
  Route::middleware('role:admin,storekeeper')->group(function () {

       
        
       
        // Stock Adjustments

        /*
        |--------------------------------------------------------------------------
        | Suppliers
        |--------------------------------------------------------------------------
        */
    
        Route::get('/suppliers', [SupplierController::class,'index'])
            ->name('suppliers.index');
    
        Route::post('/suppliers', [SupplierController::class,'store'])
            ->name('suppliers.store');
    
        Route::get('/suppliers/{id}/edit', [SupplierController::class,'edit'])
            ->name('suppliers.edit');
    
        Route::put('/suppliers/{id}', [SupplierController::class,'update'])
            ->name('suppliers.update');
    
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');
        
        Route::patch('/suppliers/{supplier}/activate', 
            [SupplierController::class, 'activate'])
            ->name('suppliers.activate');

        
    /*
        |--------------------------------------------------------------------------
        | Purchases
        |--------------------------------------------------------------------------
        */

        Route::resource('purchase', PurchaseController::class);

        Route::get(
            '/purchase/{purchase}/receipt',
            [PurchaseController::class,'receipt']
        )->name('purchase.receipt');


         /*
        |--------------------------------------------------------------------------
        | Purchase Returns
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'purchase-returns',
            PurchaseReturnController::class
        );


        Route::get(
            '/purchase-returns/purchase/{purchase}',
            [PurchaseReturnController::class,'getPurchase']
        )->name('purchase-returns.purchase');

         /*
        |--------------------------------------------------------------------------
        | Stock Adjustment
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'stock-adjustments',
            StockAdjustmentController::class
        );




    });    

   

    
    Route::middleware('role:admin,pharmacist,cashier')->group(function () {
       /*
       |--------------------------------------------------------------------------
       | Customers
       |--------------------------------------------------------------------------
       */
   
       Route::get('/customers', [CustomerController::class,'index'])
           ->name('customers.index');
   
       Route::post('/customers', [CustomerController::class,'store'])
           ->name('customers.store');
   
       Route::get('/customers/{id}/edit', [CustomerController::class,'edit'])
           ->name('customers.edit');
   
       Route::put('/customers/{id}', [CustomerController::class,'update'])
           ->name('customers.update');
   
       Route::delete('/customers/{id}', [CustomerController::class,'destroy'])
           ->name('customers.destroy');
        
       Route::patch('/customers/{customer}/toggle-status',
            [CustomerController::class, 'toggleStatus']
        )->name('customers.toggleStatus');

        
       
        // Sales Returns
        // Drafts

        /*
        |--------------------------------------------------------------------------
        | Sales
        |--------------------------------------------------------------------------
        */
    
        Route::get('/sales', [SaleController::class,'index'])
            ->name('sales.index');
    
        Route::post('/sales/store', [SaleController::class,'store'])
            ->name('sales.store');
    
        Route::get('/sales/history', [SaleController::class,'history'])
            ->name('sales.history');
    
        Route::get('/sales/{sale}/receipt', [SaleController::class,'receipt'])
            ->name('sales.receipt');
    
        Route::get('/sales/{sale}', [SaleController::class,'show'])
            ->name('sales.show');
    
        Route::post('/sales/customerType', [SaleController::class,'customerType'])
            ->name('sales.customerType');

            /*
            |--------------------------------------------------------------------------
            | Sales Returns
            |--------------------------------------------------------------------------
            */
        
            Route::get(
                '/sales-returns/sale/{sale}',
                [SalesReturnController::class,'getSale']
            )->name('sales-returns.sale');
        
        
        
            Route::resource(
                'sales-returns',
                SalesReturnController::class
                );

                /*
                |--------------------------------------------------------------------------
                | Sale Drafts
                |--------------------------------------------------------------------------
                */
            
                Route::prefix('drafts')->group(function () {
            
            
                    Route::post('/new', [SaleDraftController::class,'create'])
                        ->name('drafts.new');
            
            Route::get('/list', function () {

    $drafts = \App\Models\SaleDraft::with('items')
        ->where('user_id', Auth::id())
        ->whereIn('status', ['open','held'])
        ->latest()
        ->get();

    return view(
        'sales.draft_list',
        compact('drafts')
    );

})->name('drafts.list');
            
                    Route::get('/', [SaleDraftController::class,'index'])
                        ->name('drafts.index');
            
            
                    Route::post('/{draft}/add-item', [SaleDraftController::class,'addItem'])
                        ->name('drafts.addItem');
            
            
                    Route::delete('/items/{item}', [SaleDraftController::class,'removeItem'])
                        ->name('drafts.removeItem');
            
            
                    Route::get('/{draft}/print', [SaleDraftController::class,'printDraft'])
                        ->name('drafts.print');
            
            
                    Route::post('/{draft}/customer', [SaleDraftController::class,'updateCustomer'])
                        ->name('drafts.customer');
            
            
                    Route::get('/{draft}', [SaleDraftController::class,'show'])
                        ->name('drafts.show');
            
            
                    Route::delete('/{draft}', [SaleDraftController::class,'destroy'])
                        ->name('drafts.destroy');
            
            
                    Route::patch('/items/{item}/quantity', [SaleDraftController::class,'updateQuantity'])
                        ->name('drafts.updateQuantity');
            
            
                    Route::delete('/{draft}/clear', [SaleDraftController::class,'clear'])
                        ->name('drafts.clear');
            
            
                    Route::patch('/{draft}/hold', [SaleDraftController::class,'hold'])
                        ->name('drafts.hold');
                    Route::patch('/drafts/{draft}/resume', [SaleDraftController::class, 'resume'])
                        ->name('drafts.resume');
            
                });

                Route::get('/medicines', [MedicineController::class,'index'])
                     ->name('medicines.index');
                 Route::get('/medicines/search', [MedicineController::class,'search'])
                    ->name('medicines.search');
    });    

Route::prefix('reports')
    ->middleware('role:admin')
    ->group(function () {

        Route::get('/', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');

        Route::get('/purchases', [ReportController::class, 'purchases'])
            ->name('reports.purchases');

        Route::get('/inventory', [ReportController::class, 'inventory'])
            ->name('reports.inventory');

        Route::get('/profit', [ReportController::class, 'profit'])
            ->name('reports.profit');

        Route::get('/medicines', [ReportController::class, 'medicines'])
            ->name('reports.medicines');

        Route::get('/customers', [ReportController::class, 'customers'])
            ->name('reports.customers');
        
        Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])
        ->name('reports.low-stock');

         Route::get('/expiry', [ReportController::class, 'expiry'])
           ->name('reports.expiry');

        Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])
            ->name('reports.sales.pdf');

        Route::get('/reports/purchases/pdf', [ReportController::class, 'purchasesPdf'])
            ->name('reports.purchases.pdf');

        Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])
            ->name('reports.inventory.pdf');

        Route::get('/reports/medicines/pdf', [ReportController::class, 'medicinesPdf'])
            ->name('reports.medicines.pdf');

        Route::get('/reports/customers/pdf', [ReportController::class, 'customersPdf'])
            ->name('reports.customers.pdf');

        Route::get(
            '/reports/expenses',
            [ReportController::class,'expenses']
        )
        ->name('reports.expenses');
        Route::get(
            '/expenses/export/pdf',
            [ExpenseController::class,'exportPdf']
        )
        ->name('expenses.exportPdf');
    });


    /*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')->group(function () {

    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::post('/settings', [SettingController::class, 'store'])
        ->name('settings.store');
    
      /*
    |--------------------------------------------------------------------------
    | Database Backup
    |--------------------------------------------------------------------------
    */

    Route::get('/backup',
    [BackupController::class,'index']
    )->name('backup.index');


    Route::get('/backup/download/{file}',
        [BackupController::class,'download']
    )->name('backup.download');


    Route::get('/backup/create',
        [BackupController::class,'create']
    )->name('backup.create');

    Route::post('/backup/restore',
    [BackupController::class,'restore']
    )->name('backup.restore');

   Route::delete('/backup/{file}', [BackupController::class, 'destroy'])
    ->name('backup.destroy')
    ->middleware('role:admin');
});

  Route::middleware('role:admin')->group(function(){

    Route::get(
        '/activities',
        [ActivityController::class,'index']
    )
    ->name('activities.index');

});


   
Route::prefix('notifications')->group(function () {

    Route::get('/', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    Route::delete('/{notification}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
     // Delete only read notifications
    Route::delete('/clear-read', [NotificationController::class, 'clearRead'])
        ->name('notifications.clearRead');

    // Delete all notifications
    Route::delete('/clear-all', [NotificationController::class, 'clearAll'])
        ->name('notifications.clearAll');

    Route::get('/latest', [NotificationController::class, 'latest'])
        ->name('notifications.latest');

});

Route::resource('expense-categories', ExpenseCategoryController::class);

Route::resource('expenses', ExpenseController::class);


Route::get(
    '/reports/profit-loss',
    [ProfitLossController::class, 'index']
)->name('reports.profit-loss');


});

 
/*
|--------------------------------------------------------------------------
| Breeze Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';