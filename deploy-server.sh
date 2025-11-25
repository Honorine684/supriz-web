#!/bin/bash

# 🚀 Script de déploiement sur serveur Hostinger
# À exécuter EN SSH sur le serveur après un git pull

set -e  # Arrêter en cas d'erreur

echo "🚀 Déploiement Supriz sur Hostinger..."
echo ""

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# 1. Sauvegarde du .env
echo -e "${YELLOW}💾 Sauvegarde du .env...${NC}"
cp .env .env.backup
echo -e "${GREEN}✅ .env sauvegardé${NC}"
echo ""

# 2. Pull les changements depuis GitLab
echo -e "${YELLOW}📥 Récupération des derniers changements...${NC}"
git pull origin master

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erreur lors du git pull${NC}"
    echo -e "${YELLOW}💡 Restauration du .env...${NC}"
    cp .env.backup .env
    exit 1
fi

echo -e "${GREEN}✅ Code mis à jour${NC}"
echo ""

# 3. Restaurer le .env (au cas où il aurait été écrasé)
echo -e "${YELLOW}🔧 Restauration du .env...${NC}"
cp .env.backup .env
echo -e "${GREEN}✅ .env restauré${NC}"
echo ""

# 4. Installer/mettre à jour les dépendances Composer (si nécessaire)
if [ -f "composer.lock" ]; then
    echo -e "${YELLOW}📦 Mise à jour des dépendances Composer...${NC}"
    php ~/composer.phar install --no-dev --optimize-autoloader --no-interaction
    echo -e "${GREEN}✅ Dépendances installées${NC}"
    echo ""
fi

# 5. Migrer la base de données
echo -e "${YELLOW}🗄️  Migration de la base de données...${NC}"
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erreur lors des migrations${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Migrations exécutées${NC}"
echo ""

# 6. Copier les nouveaux fichiers public vers public_html
echo -e "${YELLOW}📂 Mise à jour des fichiers publics...${NC}"
rsync -av --delete public/ public_html/ --exclude=storage
echo -e "${GREEN}✅ Fichiers publics mis à jour${NC}"
echo ""

# 7. Nettoyer tous les caches
echo -e "${YELLOW}🧹 Nettoyage des caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear 2>/dev/null || true
echo -e "${GREEN}✅ Caches nettoyés${NC}"
echo ""

# 8. Re-cacher pour la production
echo -e "${YELLOW}⚡ Optimisation pour la production...${NC}"
php artisan config:cache
php artisan route:cache
echo -e "${GREEN}✅ Optimisations appliquées${NC}"
echo ""

# 9. Fixer les permissions
echo -e "${YELLOW}🔐 Configuration des permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chmod 600 .env
echo -e "${GREEN}✅ Permissions configurées${NC}"
echo ""

# 10. Résumé
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}🎉 Déploiement terminé avec succès !${NC}"
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "${YELLOW}📋 Actions effectuées :${NC}"
echo "  ✓ Code mis à jour depuis GitLab"
echo "  ✓ Base de données migrée"
echo "  ✓ Fichiers publics synchronisés"
echo "  ✓ Caches nettoyés et optimisés"
echo "  ✓ Permissions configurées"
echo ""
echo -e "${YELLOW}🌐 Site : http://suprizs.com${NC}"
echo ""
