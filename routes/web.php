<?php
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\MaisonController;
    use App\Http\Controllers\ClientController;
    use App\Http\Controllers\StockController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\PubliciteController;
    use App\Http\Controllers\NotificationVenteController;
    use App\Http\Controllers\NotificationCompteController;
    use App\Http\Controllers\NotificationSuprizController;
    use App\Http\Controllers\RapportJournalierController;
    use App\Http\Controllers\PublierArticlesController;
    use App\Http\Controllers\CategorieController;
    use App\Http\Controllers\LoginController;
    use App\Http\Controllers\ParametreController;
    use App\Http\Controllers\DepensesController;
    use App\Http\Controllers\FaqController;
    use App\Http\Controllers\MusiqueController;
    use App\Http\Controllers\MessageController;
    use App\Http\Controllers\ContactController;


    Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'home' ])->name('home');
    Route::get('/maison', [MaisonController::class, 'maison'])->name('maison');
    Route::get('/client', [ClientController::class, 'client'])->name('client');
    Route::get('/search-clients', [ClientController::class, 'searchClients'])->name('search.clients');
    Route::get('/stocks', [StockController::class, 'stocks'])->name('stocks');
    Route::post('/stocks/modif-statut', [StockController::class, 'changerStatut'])->name('produit.changerStatut');
    Route::delete('/produit/{id}', [StockController::class, 'destroyProduit'])->name('produit.destroy');
    Route::get('/messages/notification', [NotificationController::class, 'notification'])->name('notification');
    Route::get('/messages/publicite', [PubliciteController::class, 'publicite'])->name('publicite');
    Route::get('/notification/vente', [NotificationVenteController::class, 'notificationVente'])->name('notificationVente');
    Route::post('/notification/vente/close', [NotificationVenteController::class, 'closeShop'])->name('close.shop');
    Route::get('/notification/compte', [NotificationCompteController::class, 'notificationCompte'])->name('notificationCompte');
    Route::get('/search-users', [NotificationCompteController::class, 'search'])->name('search.users');
    Route::get('/notification/supriz', [NotificationSuprizController::class, 'notificationSupriz'])->name('notificationSupriz');
    Route::post('/notification/supriz/close', [NotificationSuprizController::class, 'closeSupriz'])->name('close.supriz');
    Route::get('/rapport-journalier', [RapportJournalierController::class, 'rapportJournalier'])->name('rapportJournalier');
    Route::get('/publier-articles', [PublierArticlesController::class, 'publierArticles'])->name('publierArticles');
    Route::get('/categorie', [CategorieController::class, 'categorie'])->name('categorie');
    Route::delete('/categorie/{id}', [CategorieController::class, 'deleteCategorie'])->name('categorie.destroy');
    // Route pour afficher le formulaire de modification (avec pré-remplissage des champs)
    Route::get('/categorie/edit/{id}', [CategorieController::class, 'edit'])->name('categorie.edit');
    // Route pour mettre à jour une sous-catégorie existante
    Route::put('/categorie/{id}', [CategorieController::class, 'update'])->name('categorie.update');
    Route::post('/add-categorie', [CategorieController::class, 'store'])->name('add.categorie');
    Route::post('/categorie/{id}/toggle-status', [CategorieController::class, 'toggleStatus'])->name('categorie.toggleStatus');
    Route::post('/categorie/{id}/update-image', [CategorieController::class, 'updateSuprizImage'])->name('categorie.updateSuprizImage');
    Route::post('/categorie/{id}/update-icone', [CategorieController::class, 'updateSuprizIcone'])->name('categorie.updateSuprizIcone');
    Route::get('/parametre', [ParametreController::class, 'parametre'])->name('parametre');
    Route::post('/updateprofil', [ParametreController::class, 'updateprofil'])->name('updateprofil');
    Route::get('/faq', [FaqController::class, 'faq'])->name('faq');
    Route::delete('/faq/{id}', [FaqController::class, 'deleteFaq'])->name('faq.destroy');
    Route::post('/faq/store', [FaqController::class, 'store'])->name('store.faq');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notification.store');
    Route::post('/get-sous-categories', [PublierArticlesController::class, 'getSousCategories']);
    Route::post('/article/store', [PublierArticlesController::class, 'store'])->name('article.store');
    Route::get('/article/{id}/edit', [PublierArticlesController::class, 'edit'])->name('article.edit');
    Route::put('/article/{id}', [PublierArticlesController::class, 'update'])->name('article.update');
    Route::post('/publicite/store', [PubliciteController::class, 'store'])->name('publicite.store');
    Route::delete('/publicite/{id}', [PubliciteController::class, 'deletePublicite'])->name('publicite.destroy');
    Route::delete('/depenses/{id}', [DepensesController::class, 'deleteDepenses'])->name('depenses.destroy');
    Route::get('/depenses', [DepensesController::class, 'depenses'])->name('depenses');
    Route::put('/depenses/{id}/update', [DepensesController::class, 'update'])->name('depenses.update');
    Route::get('/depenses/{id}/edit', [DepensesController::class, 'edit'])->name('depenses.edit');
    Route::post('/depenses/store', [DepensesController::class, 'store'])->name('depenses.store');
    Route::get('/faq/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('/faq/update/{id}', [FaqController::class, 'update'])->name('faq.update');

    // Routes pour les musiques
    Route::get('/musique', [MusiqueController::class, 'index'])->name('musique');
    Route::post('/musique/store', [MusiqueController::class, 'store'])->name('musique.store');
    Route::put('/musique/{id}', [MusiqueController::class, 'update'])->name('musique.update');
    Route::post('/musique/{id}/toggle-status', [MusiqueController::class, 'toggleStatus'])->name('musique.toggleStatus');
    Route::delete('/musique/{id}', [MusiqueController::class, 'destroy'])->name('musique.destroy');
});

// Route publique pour la page d'accueil
Route::get('/welcome', [MessageController::class, 'welcome'])->name('welcome');

// Routes publiques pour les messages surprise
Route::get('/nouveau-message', [MessageController::class, 'index'])->name('nouveau.message');
Route::post('/message/store', [MessageController::class, 'store'])->name('message.store');
Route::get('/message/{slug}', [MessageController::class, 'show'])->name('message.show');
Route::get('/api/musiques/categorie/{categorieId}', [MusiqueController::class, 'getMusiquesByCategorie'])->name('musiques.by.categorie');
Route::get('/api/categories-supriz', [MessageController::class, 'getCategoriesSupriz'])->name('categories.supriz');

// Page politique de confidentialité
Route::get('/politique-confidentialite', function () {
    return view('politique-confidentialite');
})->name('politique.confidentialite');

// Page conditions d'utilisation
Route::get('/conditions-utilisation', function () {
    return view('conditions-utilisation');
})->name('conditions.utilisation');

// Route pour le formulaire de contact
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');