<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SousCategorie;
use App\Models\Categorie;

class SuprizCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie Supriz
        $suprizCategorie = Categorie::where('name_id_categories', 's')->first();

        if (!$suprizCategorie) {
            $this->command->error('Catégorie Supriz non trouvée !');
            return;
        }

        // Liste des sous-catégories Supriz prédéfinies avec leurs placeholders et icônes
        $sousCategories = [
            [
                'name' => 'Anniversaire',
                'placeholder' => 'Ex: Joyeux anniversaire Admin',
                'icone' => 'categories/Birthday Cake.png',
            ],
            [
                'name' => 'Mariage',
                'placeholder' => 'Ex: Félicitations pour votre mariage',
                'icone' => 'categories/Layer 2.png',
            ],
            [
                'name' => 'Vœux',
                'placeholder' => 'Ex: Meilleurs vœux pour cette nouvelle année',
                'icone' => 'categories/Image.png',
            ],
            [
                'name' => 'Nouvel an',
                'placeholder' => 'Ex: Bonne année 2025 !',
                'icone' => 'categories/Image-1.png',
            ],
            [
                'name' => 'Noël',
                'placeholder' => 'Ex: Joyeux Noël et bonnes fêtes',
                'icone' => 'categories/christmas.png',
            ],
        ];

        foreach ($sousCategories as $sousCategorie) {
            SousCategorie::updateOrCreate(
                [
                    'type_categorie_id' => $suprizCategorie->id,
                    'name_sous_categories' => $sousCategorie['name'],
                ],
                [
                    'placeholder_texte' => $sousCategorie['placeholder'],
                    'icone' => $sousCategorie['icone'],
                    'prix' => null,
                    'image' => null,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('5 sous-catégories Supriz créées avec succès !');
    }
}
