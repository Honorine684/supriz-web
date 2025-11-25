# 🚀 Guide de Déploiement Supriz sur Hostinger

## 📋 Pré-requis
- Compte Hostinger Business activé
- Accès SSH configuré
- Domaine configuré dans hPanel

---

## 🔧 ÉTAPE 1 : Configuration dans hPanel

### 1.1 Créer la Base de Données MySQL
1. Allez dans **hPanel → Bases de données → Gérer**
2. Créez une nouvelle base de données :
   - Nom : `u123456789_supriz` (notez le nom exact)
   - Utilisateur : `u123456789_supriz_user`
   - Mot de passe : (générez un mot de passe fort)
3. **NOTEZ CES INFORMATIONS** pour le .env

### 1.2 Configurer l'Email (optionnel)
1. Allez dans **hPanel → Emails**
2. Créez une adresse : `contact@votredomaine.com`
3. Notez le mot de passe pour le .env

### 1.3 Activer SSH
1. Allez dans **hPanel → Avancé → SSH Access**
2. Activez l'accès SSH
3. Notez les informations de connexion

---

## 📦 ÉTAPE 2 : Préparer les Fichiers Localement

### 2.1 Installer les dépendances
```bash
composer install --optimize-autoloader --no-dev
```

### 2.2 Créer une archive
```bash
# Depuis la racine du projet
tar -czf supriz-deploy.tar.gz \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  --exclude='.env' \
  --exclude='public/storage' \
  .
```

---

## 🌐 ÉTAPE 3 : Upload sur Hostinger

### Option A : Via File Manager (plus simple)
1. Connectez-vous à **hPanel → File Manager**
2. Allez dans `/domains/votredomaine.com/`
3. Uploadez `supriz-deploy.tar.gz`
4. Clic droit → Extract
5. Supprimez l'archive après extraction

### Option B : Via SSH (recommandé)
```bash
# Depuis votre machine locale
scp supriz-deploy.tar.gz u123456789@votredomaine.com:~/domains/votredomaine.com/

# Connectez-vous en SSH
ssh u123456789@votredomaine.com

# Allez dans le dossier
cd domains/votredomaine.com

# Extrayez
tar -xzf supriz-deploy.tar.gz

# Supprimez l'archive
rm supriz-deploy.tar.gz
```

---

## ⚙️ ÉTAPE 4 : Configuration SSH

Connectez-vous en SSH :
```bash
ssh u123456789@votredomaine.com
cd domains/votredomaine.com
```

### 4.1 Créer le fichier .env
```bash
# Copiez le template
cp .env.hostinger .env

# Éditez avec nano ou vim
nano .env
```

**Modifiez ces lignes :**
```env
APP_URL=https://votredomaine.com

# Base de données (infos de l'étape 1.1)
DB_DATABASE=u123456789_supriz
DB_USERNAME=u123456789_supriz_user
DB_PASSWORD=VOTRE_MOT_DE_PASSE_BDD

# Email (infos de l'étape 1.2)
MAIL_HOST=mail.votredomaine.com
MAIL_USERNAME="contact@votredomaine.com"
MAIL_PASSWORD="VOTRE_MOT_DE_PASSE_EMAIL"
```

### 4.2 Configurer les permissions
```bash
# Permissions pour storage et cache
chmod -R 775 storage bootstrap/cache
chmod -R 777 storage/logs
chmod -R 777 storage/framework/sessions
chmod -R 777 storage/framework/views
chmod -R 777 storage/framework/cache
```

### 4.3 Déplacer les fichiers public vers public_html
```bash
# Déplacez tout le contenu de public/ vers public_html/
cp -r public/* public_html/

# Supprimez le dossier public
rm -rf public
```

### 4.4 Créer le fichier .htaccess
```bash
cd public_html
nano .htaccess
```

Collez ce contenu :
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Rediriger vers le dossier parent si nécessaire
    RewriteCond %{REQUEST_URI} !^/public_html/
    RewriteRule ^(.*)$ /public_html/$1 [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 4.5 Migrer la base de données
```bash
cd ~/domains/votredomaine.com

# Effacer le cache de config
php artisan config:clear
php artisan cache:clear

# Migrer la base de données
php artisan migrate --force

# Lancer les seeders (IMPORTANT pour votre projet)
php artisan db:seed --class=SuprizCategoriesSeeder --force
php artisan db:seed --class=MusiquesPredefinesSeeder --force
```

### 4.6 Optimiser pour la production
```bash
# Optimiser l'autoloader
composer dump-autoload --optimize

# Cacher les configs
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔒 ÉTAPE 5 : Sécurité

### 5.1 Protéger le .env
```bash
cd ~/domains/votredomaine.com
chmod 600 .env
```

### 5.2 Vérifier les fichiers sensibles
Assurez-vous que ces fichiers ne sont PAS dans public_html :
- `.env`
- `composer.json`
- `artisan`
- Dossiers : `app/`, `database/`, `routes/`

---

## ✅ ÉTAPE 6 : Vérification

### 6.1 Tester le site
Visitez : `https://votredomaine.com`

### 6.2 Vérifier les logs en cas d'erreur
```bash
tail -f ~/domains/votredomaine.com/storage/logs/laravel.log
```

### 6.3 Tests fonctionnels
- [ ] Page d'accueil charge correctement
- [ ] Images et CSS chargent
- [ ] Catégories s'affichent (FAQ, Messages, etc.)
- [ ] Musiques prédéfinies disponibles
- [ ] Formulaires fonctionnent

---

## 🎯 Commandes Utiles

### Vider le cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Voir les logs
```bash
tail -f storage/logs/laravel.log
```

### Relancer les migrations (⚠️ EFFACE TOUTES LES DONNÉES)
```bash
php artisan migrate:fresh --seed --force
```

### Créer un cronjob pour Laravel Scheduler (si besoin)
Dans hPanel → Cronjobs, ajoutez :
```
/usr/bin/php /home/u123456789/domains/votredomaine.com/artisan schedule:run
```
Fréquence : Toutes les minutes

---

## 🆘 Dépannage

### Erreur 500
1. Vérifiez les permissions : `chmod -R 775 storage bootstrap/cache`
2. Vérifiez le .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
3. Vérifiez les logs : `tail storage/logs/laravel.log`

### CSS/Images ne chargent pas
1. Vérifiez que les fichiers sont dans `public_html/`
2. Vérifiez le APP_URL dans .env
3. Videz le cache : `php artisan cache:clear`

### Erreur de connexion base de données
1. Vérifiez les credentials dans .env
2. Testez la connexion depuis hPanel → phpMyAdmin
3. Vérifiez que DB_HOST=localhost (pas 127.0.0.1)

### Page blanche
1. Activez le debug temporairement : `APP_DEBUG=true` dans .env
2. Rechargez la page pour voir l'erreur
3. Remettez `APP_DEBUG=false` après correction

---

## 📝 Notes Importantes

1. **Backups** : Hostinger fait des backups quotidiens automatiques
2. **SSL** : Activez le SSL gratuit dans hPanel → SSL
3. **CDN** : Le CDN gratuit est déjà actif sur votre plan
4. **Mises à jour** : Pour mettre à jour :
   - Uploadez les nouveaux fichiers
   - Lancez `php artisan migrate --force`
   - Videz les caches

---

## 🎉 C'est Terminé !

Votre application Supriz est maintenant en ligne sur Hostinger !

**Support :**
- Documentation Hostinger : https://support.hostinger.com
- Laravel Docs : https://laravel.com/docs
