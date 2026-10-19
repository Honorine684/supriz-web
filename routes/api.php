<?php

use App\Http\Controllers\Api\ApiNotificationController;
use App\Http\Controllers\Api\ApiMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PanierController;
use App\Http\Controllers\Api\PubliciteController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ApiHistoriqueController;
use App\Http\Controllers\Api\ApiFriendBirthdayController;
use App\Http\Controllers\Api\CronController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [UserController::class, 'createUser']);
Route::post('login', [UserController::class, 'loginUser']);
Route::post('social-login', [UserController::class, 'socialLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserController::class, 'logout']); // Ajout de la route de déconnexion

    Route::post('ajouter-au-panier', [PanierController::class, 'addToCart']);
    Route::delete('remove-from-cart/delete/{achat_id}', [PanierController::class, 'removeFromCart']);
    Route::post('/cart/remove-selected', [PanierController::class, 'removeSelectedItems']);
    Route::post('update-quantity', [PanierController::class, 'updateQuantity']);

    Route::post('numero-beneficiere', [PanierController::class, 'updateBeneficiere']);

    Route::post('deletecompte', [UserController::class, 'dropUserInTable']);
    Route::get('panier-product', [PanierController::class, 'fetchAchats']);
    Route::get('faq', [FaqController::class, 'faqapp']);
    Route::get('/notifications', [ApiNotificationController::class, 'index']);
    Route::post('/notifications/makereadall', [ApiNotificationController::class, 'makereadAll']);
    Route::delete('/notifications/delete/{notificationId}', [ApiNotificationController::class, 'deleteAtId']);
    Route::delete('/notifications/deleteall', [ApiNotificationController::class, 'deleteAll']);
    Route::post('/notifications/makeread/{id}', [ApiNotificationController::class, 'markAsRead']);

    Route::get('/products', [ProductController::class, 'getProductsByCategory']);
    Route::get('/all-products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/subcategories/{categoryId}', [CategoryController::class, 'getSubcategories']);

    Route::post('/update-password', [UserController::class, 'updatePassword']);
    Route::post('/update-profile-image', [UserController::class, 'updateProfileImage']);
    Route::post('/update-name', [UserController::class, 'updateName']);
    Route::post('/update-email', [UserController::class, 'updateEmail']);
    Route::post('/update-number', [UserController::class, 'updateNumber']);
    Route::post('/update-fcm-token', [UserController::class, 'updateFcmToken']);
    Route::get('/historiques', [ApiHistoriqueController::class, 'index'])->name('historiques.index');

    Route::post('/create-transaction', [PanierController::class, 'createTransaction']);
    Route::get('/transaction-status', [PanierController::class, 'transactionStatus']);

    // Routes Messages (authentifiées)
    Route::get('/messages', [ApiMessageController::class, 'index']);
    Route::post('/messages', [ApiMessageController::class, 'store']);
    Route::get('/messages/{id}', [ApiMessageController::class, 'show']);
    Route::delete('/messages/{id}', [ApiMessageController::class, 'destroy']);
    Route::post('/messages/{id}/toggle-status', [ApiMessageController::class, 'toggleStatus']);
    Route::get('/messages-categories', [ApiMessageController::class, 'getCategories']);
    Route::get('/messages-musiques', [ApiMessageController::class, 'getMusiques']);
    Route::get('/messages-musiques/{categorieId}', [ApiMessageController::class, 'getMusiques']);
    Route::get('/messages-arriere-plans', [ApiMessageController::class, 'getArrierePlans']);
    Route::get('/messages-arriere-plans/{categorieId}', [ApiMessageController::class, 'getArrierePlans']);

    // Routes Anniversaires (authentifiées)
    Route::get('/birthdays', [ApiFriendBirthdayController::class, 'index']);
    Route::get('/birthdays/upcoming', [ApiFriendBirthdayController::class, 'upcoming']);
    Route::get('/birthdays/today', [ApiFriendBirthdayController::class, 'today']);
    Route::post('/birthdays', [ApiFriendBirthdayController::class, 'store']);
    Route::put('/birthdays/{id}', [ApiFriendBirthdayController::class, 'update']);
    Route::delete('/birthdays/{id}', [ApiFriendBirthdayController::class, 'destroy']);
    Route::post('/birthdays/{id}/test-notification', [ApiFriendBirthdayController::class, 'testNotification']);
    Route::post('/birthdays/test-notification', [ApiFriendBirthdayController::class, 'testImmediateNotification']);
    Route::post('/birthdays/schedule-test', [ApiFriendBirthdayController::class, 'scheduleTestNotification']);
});

// Dans web.php ou api.php selon votre besoin
Route::get('paiement/success', [PanierController::class, 'successPage'])->name('paiement.success');

Route::get('/payment-callback', [PanierController::class, 'closePaiement'])->name('payment.callback');
Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::get('/publicites', [PubliciteController::class, 'show']);

// Routes publiques pour les messages
Route::get('/public/messages/{slug}', [ApiMessageController::class, 'showPublic']);
Route::post('/public/messages/{slug}/react', [ApiMessageController::class, 'addReaction']);
Route::post('/send-otp-mdp', [UserController::class, 'sendOtpMdp']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/verify-otp-compte', [UserController::class, 'verifyOtpCompte']);
Route::post('/update-password-mdp', [UserController::class, 'updatePasswordMdp']);

// Endpoint pour cron-job.org (notifications anniversaire)
Route::get('/cron/birthday-notifications', [CronController::class, 'sendBirthdayNotifications']);
// Endpoint pour cron-job.org (purge des notifications de +7 jours)
Route::get('/cron/prune-notifications', [CronController::class, 'pruneOldNotifications']);
Route::get('/cron/test-real-birthday', [CronController::class, 'testRealBirthday']);
