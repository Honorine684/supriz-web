#!/bin/bash

# 🚀 Script de préparation pour déploiement Hostinger
# Supriz Web Application

echo "🚀 Préparation du déploiement Supriz..."
echo ""

# Couleurs pour les messages
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Installer les dépendances production
echo -e "${YELLOW}📦 Installation des dépendances production...${NC}"
composer install --optimize-autoloader --no-dev

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erreur lors de l'installation des dépendances${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Dépendances installées${NC}"
echo ""

# 2. Nettoyer les fichiers inutiles
echo -e "${YELLOW}🧹 Nettoyage des fichiers...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo -e "${GREEN}✅ Cache nettoyé${NC}"
echo ""

# 3. Créer l'archive
echo -e "${YELLOW}📦 Création de l'archive de déploiement...${NC}"

tar -czf supriz-deploy.tar.gz \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='.env' \
  --exclude='.env.local' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/data/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  --exclude='public/storage' \
  --exclude='tests' \
  --exclude='.phpunit.cache' \
  --exclude='*.log' \
  --exclude='supriz-deploy.tar.gz' \
  .

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erreur lors de la création de l'archive${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Archive créée : supriz-deploy.tar.gz${NC}"
echo ""

# 4. Afficher la taille
SIZE=$(du -h supriz-deploy.tar.gz | cut -f1)
echo -e "${GREEN}📊 Taille de l'archive : ${SIZE}${NC}"
echo ""

# 5. Instructions finales
echo -e "${GREEN}✅ Préparation terminée !${NC}"
echo ""
echo -e "${YELLOW}📋 Prochaines étapes :${NC}"
echo "1. Uploadez supriz-deploy.tar.gz sur Hostinger"
echo "2. Extrayez l'archive dans domains/votredomaine.com/"
echo "3. Suivez les instructions dans DEPLOY_HOSTINGER.md"
echo ""
echo -e "${YELLOW}📄 Fichiers créés :${NC}"
echo "  - supriz-deploy.tar.gz (archive à uploader)"
echo "  - .env.hostinger (template pour production)"
echo "  - DEPLOY_HOSTINGER.md (guide complet)"
echo ""

# 6. Rappel important
echo -e "${RED}⚠️  IMPORTANT :${NC}"
echo "  - NE PAS uploader le fichier .env local"
echo "  - Créez un nouveau .env sur le serveur avec .env.hostinger"
echo "  - Changez APP_DEBUG=false en production"
echo ""

# 7. Réinstaller les dépendances dev (optionnel)
read -p "Voulez-vous réinstaller les dépendances de développement ? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    echo -e "${YELLOW}📦 Réinstallation des dépendances dev...${NC}"
    composer install
    echo -e "${GREEN}✅ Dépendances dev réinstallées${NC}"
fi

echo ""
echo -e "${GREEN}🎉 Prêt pour le déploiement !${NC}"
