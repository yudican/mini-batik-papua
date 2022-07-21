<?php

use App\Http\Controllers\AuthController;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Banner\BannerController;
use App\Http\Livewire\Chat\Chat;
use App\Http\Livewire\Chat\ChatDetail;
use App\Http\Livewire\Client\Checkout;
use App\Http\Livewire\Client\CheckoutPayment;
use App\Http\Livewire\Client\ProductDetail;
use App\Http\Livewire\Client\ShoppingCart;
use App\Http\Livewire\CrudGenerator;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\HomeUser;
use App\Http\Livewire\Invoice;
use App\Http\Livewire\Master\JenisProdukController;
use App\Http\Livewire\Master\PaymentMethodController;
use App\Http\Livewire\Master\ProdukKatalogController;
use App\Http\Livewire\Order\OrderController;
use App\Http\Livewire\Produk\ProdukController;
use App\Http\Livewire\Settings\Menu;
use App\Http\Livewire\Transaksi\ProdukTransaksiController;
use App\Http\Livewire\UpdateProfile;
use App\Http\Livewire\UserManagement\Permission;
use App\Http\Livewire\UserManagement\PermissionRole;
use App\Http\Livewire\UserManagement\Role;
use App\Http\Livewire\UserManagement\User;
use App\Models\ProdukTransaksi;
use Illuminate\Support\Facades\Route;

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

Route::get('/', HomeUser::class)->name('home.user');
Route::get('/product-detail/{produk_id}', ProductDetail::class)->name('product-detail');
Route::post('login', [AuthController::class, 'login'])->name('admin.login');
Route::group(['middleware' => ['auth:sanctum', 'verified', 'user.authorization']], function () {
    // Crud Generator Route
    Route::get('/crud-generator', CrudGenerator::class)->name('crud.generator');

    // user management
    Route::get('/permission', Permission::class)->name('permission');
    Route::get('/permission-role/{role_id}', PermissionRole::class)->name('permission.role');
    Route::get('/role', Role::class)->name('role');
    Route::get('/user', User::class)->name('user');
    Route::get('/menu', Menu::class)->name('menu');

    // App Route
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master data
    Route::get('/master/katalog-produk', ProdukKatalogController::class)->name('katalog-produk');
    Route::get('/master/jenis-produk', JenisProdukController::class)->name('jenis-produk');
    Route::get('/master/metode-pembayaran', PaymentMethodController::class)->name('metode-pembayaran');
    Route::get('/master/banner', BannerController::class)->name('banner');

    // Data
    Route::get('/list-produk', ProdukController::class)->name('list-produk');
    Route::get('/transaksi-masuk', ProdukTransaksiController::class)->name('transaksi-masuk');
    Route::get('/transaksi-keluar', ProdukTransaksiController::class)->name('transaksi-keluar');
    // client
    Route::get('/keranjang-saya', ShoppingCart::class)->name('cart');
    Route::get('/checkout/{order_id?}', Checkout::class)->name('checkout');
    Route::get('/selesaikan-pesanan/{order_id}', CheckoutPayment::class)->name('checkout.payment');
    // Route::get('panduan-admin', PanduanAdmin::class)->name('admin.panduan');

    // Order Data
    Route::get('/order', OrderController::class)->name('order');
    Route::get('/invoice/{order_id}', Invoice::class)->name('invoice');
    Route::get('/update-profile', UpdateProfile::class)->name('update-profile');

    Route::get('/chat', Chat::class)->name('chat');
    Route::get('/chat/detail/{chat_id}', ChatDetail::class)->name('chat.detail');
});
