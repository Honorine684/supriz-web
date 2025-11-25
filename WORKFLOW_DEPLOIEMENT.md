# 🚀 Workflow de Déploiement Supriz avec GitLab

## 📋 Configuration Initiale (À faire une seule fois)

### 1. Créer un dépôt GitLab

1. Allez sur **https://gitlab.com**
2. Créez un nouveau projet : **supriz-web**
3. Notez l'URL du dépôt : `https://gitlab.com/VOTRE_USERNAME/supriz-web.git`

### 2. Configurer Git en local

```bash
cd /Users/macbookpro/Documents/work/work/Projet/Supriz/supriz-web

# Initialiser git (si pas déjà fait)
git init

# Ajouter GitLab comme remote
git remote add origin https://gitlab.com/VOTRE_USERNAME/supriz-web.git

# Premier commit
git add .
git commit -m "Initial commit - Supriz web application"

# Push vers GitLab
git push -u origin master
```

### 3. Configurer Git sur le serveur Hostinger

**En SSH sur le serveur :**

```bash
cd ~/domains/suprizs.com

# Initialiser git
git init

# Ajouter le remote GitLab
git remote add origin https://gitlab.com/VOTRE_USERNAME/supriz-web.git

# Configurer pour éviter les conflits
git config pull.rebase false
```

### 4. Créer le script de déploiement sur le serveur

**Upload `deploy-server.sh` sur le serveur :**

Via File Manager ou SCP depuis votre Mac :
```bash
scp /Users/macbookpro/Documents/work/work/Projet/Supriz/supriz-web/deploy-server.sh u732638161@suprizs.com:~/domains/suprizs.com/
```

**Puis en SSH :**
```bash
cd ~/domains/suprizs.com
chmod +x deploy-server.sh
```

---

## 🔄 Workflow de Déploiement Quotidien

### Sur votre Mac (développement local) :

```bash
cd /Users/macbookpro/Documents/work/work/Projet/Supriz/supriz-web

# 1. Faites vos modifications de code
# 2. Testez localement

# 3. Commitez vos changements
git add .
git commit -m "Description de vos changements"

# 4. Pushez vers GitLab
git push origin master
```

### Sur le serveur Hostinger (en SSH) :

```bash
cd ~/domains/suprizs.com

# Exécutez le script de déploiement
./deploy-server.sh
```

**C'est tout ! Le script s'occupe de tout automatiquement.**

---

## 🛡️ Protection des Données

Le script `deploy-server.sh` **protège automatiquement** :
- ✅ Le fichier `.env` (sauvegarde avant pull)
- ✅ Le dossier `storage/` (fichiers uploadés par les utilisateurs)
- ✅ Les fichiers publics (synchronisation sélective)

---

## 📝 Commandes Utiles

### Voir les changements avant de déployer
```bash
# En local
git status
git diff

# Sur le serveur (voir ce qui va changer)
cd ~/domains/suprizs.com
git fetch origin
git diff HEAD origin/master
```

### Rollback en cas de problème
```bash
# Sur le serveur
cd ~/domains/suprizs.com

# Revenir au commit précédent
git reset --hard HEAD~1

# Restaurer le .env
cp .env.backup .env

# Nettoyer
php artisan config:clear
php artisan cache:clear
```

### Voir les logs en cas d'erreur
```bash
# Logs Laravel
tail -50 storage/logs/laravel.log

# Logs Apache (si disponibles)
tail -50 ~/logs/error_log
```

---

## ⚡ Déploiement Rapide (sans Git)

Si vous voulez déployer sans Git :

**En local :**
```bash
./prepare-deploy.sh
scp supriz-deploy.tar.gz u732638161@suprizs.com:~/
```

**En SSH :**
```bash
cd ~/domains/suprizs.com
cp .env .env.backup
tar -xzf ~/supriz-deploy.tar.gz
cp .env.backup .env
php artisan migrate --force
php artisan config:clear && php artisan cache:clear
rm ~/supriz-deploy.tar.gz
```

---

## 🔥 Commandes de Maintenance

### Vider tous les caches
```bash
cd ~/domains/suprizs.com
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear 2>/dev/null || true
```

### Voir les utilisateurs
```bash
mysql -u u732638161_supriz -p'$Mx3lqW;' u732638161_supriz -e "SELECT id, nom, email FROM users;"
```

### Créer un nouvel utilisateur
```bash
HASH=$(php -r "echo password_hash('NOUVEAU_MDP', PASSWORD_BCRYPT);")
mysql -u u732638161_supriz -p'$Mx3lqW;' u732638161_supriz -e "INSERT INTO users (nom, prenom, email, numero, password, created_at, updated_at) VALUES ('Nom', 'Prenom', 'email@example.com', '22912345678', '$HASH', NOW(), NOW());"
```

### Vérifier l'espace disque
```bash
du -sh ~/domains/suprizs.com
df -h
```

---

## ✅ Checklist Avant Chaque Déploiement

- [ ] Code testé en local
- [ ] Changements commités sur GitLab
- [ ] Connexion SSH active
- [ ] Backup du `.env` (fait automatiquement par le script)
- [ ] Lancer `./deploy-server.sh`
- [ ] Tester le site après déploiement

---

## 🆘 Dépannage

### Le site affiche une erreur 500
```bash
# Activer le debug temporairement
nano .env
# Changer APP_DEBUG=false en APP_DEBUG=true
php artisan config:clear

# Voir les logs
tail -50 storage/logs/laravel.log

# Remettre APP_DEBUG=false après diagnostic
```

### Les images/CSS ne chargent pas
```bash
cd ~/domains/suprizs.com
rsync -av --delete public/ public_html/ --exclude=storage
php artisan config:clear
```

### Problème de permissions
```bash
chmod -R 775 storage bootstrap/cache
chmod 600 .env
```

### Reconnexion MySQL
```bash
# Si timeout MySQL
php artisan config:clear
php artisan cache:clear
```

---

## 📊 Informations du Serveur

- **Domaine :** http://suprizs.com
- **SSH :** u732638161@suprizs.com
- **Chemin :** /home/u732638161/domains/suprizs.com
- **Base de données :** u732638161_supriz
- **User DB :** u732638161_supriz
- **Timeout MySQL :** 20 secondes

---

## 🎯 Bonnes Pratiques

1. **Toujours tester en local** avant de pusher
2. **Commits fréquents** avec messages clairs
3. **Déployer en dehors des heures de pointe**
4. **Vérifier le site** après chaque déploiement
5. **Garder APP_DEBUG=false** en production (sauf debug)
6. **Sauvegarder la base** régulièrement (Hostinger le fait automatiquement)

---

## 🔗 Ressources

- GitLab : https://gitlab.com/VOTRE_USERNAME/supriz-web
- hPanel : https://hpanel.hostinger.com
- Site : http://suprizs.com
- Documentation Hostinger : https://support.hostinger.com
