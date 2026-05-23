<?php

namespace Database\Seeders;

use App\Enums\ActionType;
use App\Enums\ConditionType;
use App\Enums\MediaType;
use App\Models\Guide;
use App\Models\Step;
use Illuminate\Database\Seeder;

class DemoGuideSeeder extends Seeder
{
    public function run(): void
    {
        if (Guide::where('slug', 'bellecour')->exists()) {
            return;
        }

        $bellecour = Guide::factory()->published()->create([
            'title' => 'Check-in — Appartement Bellecour, Lyon 2e',
            'description' => 'Toutes les infos pour votre arrivée dans l\'appartement. Bienvenue à Lyon !',
            'slug' => 'bellecour',
            'creator_email' => 'sophie@guidly.app',
            'cover_image_path' => 'images/demo/cover-bellecour.png',
        ]);

        $steps = [
            [
                'title' => 'Trouver l\'immeuble',
                'content_html' => '<p>L\'appartement se situe <strong>Place Bellecour</strong>, côté sud, au <strong>12 rue de la Charité, Lyon 2e</strong>.</p><p>Depuis la place Bellecour, prenez la rue de la Charité direction sud. L\'immeuble est à 50 mètres sur votre droite — façade en pierre beige avec une grande porte en bois.</p><p>Repère : il y a une boulangerie juste en face.</p>',
                'order' => 1,
                'action_type' => ActionType::Acknowledge,
                'action_label' => 'J\'ai trouvé l\'immeuble',
                'media_path' => 'images/demo/step-1-rue.png',
                'media_type' => MediaType::Image,
            ],
            [
                'title' => 'Entrer dans l\'immeuble',
                'content_html' => '<p>Le digicode est à droite de la porte principale. Tapez <strong>A1234</strong> puis appuyez sur la touche verte.</p><p>La porte se déverrouille pendant 5 secondes — poussez fermement.</p><p><em>Si le digicode ne répond pas, attendez 10 secondes et réessayez. En cas de problème, appelez le numéro affiché à côté de l\'interphone.</em></p>',
                'order' => 2,
                'action_type' => ActionType::Checkbox,
                'action_label' => 'J\'ai compris',
                'condition_type' => ConditionType::TimeRange,
                'condition_start' => '14:00',
                'condition_end' => '23:59',
                'media_path' => 'images/demo/step-2-digicode.png',
                'media_type' => MediaType::Image,
            ],
            [
                'title' => '3ème étage, porte gauche',
                'content_html' => '<p>Prenez l\'ascenseur ou l\'escalier B (à gauche en entrant).</p><p>Appartement <strong>3G</strong> — c\'est la porte de gauche en sortant de l\'ascenseur.</p><p>La clé est dans la boîte à clés accrochée à la rampe.<br>Code : <strong>7741</strong></p>',
                'order' => 3,
                'action_type' => ActionType::Checkbox,
                'action_label' => 'J\'ai compris',
                'condition_type' => ConditionType::TimeRange,
                'condition_start' => '14:00',
                'condition_end' => '23:59',
                'media_path' => 'images/demo/step-3-couloir.png',
                'media_type' => MediaType::Image,
            ],
            [
                'title' => 'Se connecter au WiFi',
                'content_html' => '<p><strong>Réseau :</strong> Bellecour-Guest<br><strong>Mot de passe :</strong> Lyon2024!</p><p>Le routeur est dans le meuble TV. Si ça ne marche pas, débranchez-le 10 secondes et rebranchez.</p>',
                'order' => 4,
                'action_type' => ActionType::None,
                'media_path' => 'images/demo/step-4-wifi.png',
                'media_type' => MediaType::Image,
            ],
            [
                'title' => 'Où se garer',
                'content_html' => '<p>Place réservée au sous-sol -1, emplacement <strong>P.47</strong> (le numéro est peint au sol).</p><p>Entrez par la rampe côté rue de la Charité. La télécommande est dans le tiroir de l\'entrée.</p>',
                'order' => 5,
                'action_type' => ActionType::Checkbox,
                'action_label' => 'J\'ai trouvé la place',
                'condition_type' => ConditionType::TimeRange,
                'condition_start' => '14:00',
                'condition_end' => '23:59',
                'media_path' => 'images/demo/step-5-parking.png',
                'media_type' => MediaType::Image,
            ],
            [
                'title' => 'Sortir les poubelles',
                'content_html' => '<p>Les poubelles sont dans la cour intérieure, accès par la petite porte à côté de l\'ascenseur au RDC.</p><p><strong>Jaune</strong> — emballages, plastique<br><strong>Verte</strong> — tout le reste<br><strong>Bac marron</strong> (à côté) — verre</p><p><em>Merci de descendre vos poubelles la veille du départ.</em></p>',
                'order' => 6,
                'action_type' => ActionType::Checkbox,
                'action_label' => 'OK, je descendrai les poubelles',
                'media_path' => 'images/demo/step-6-poubelles.png',
                'media_type' => MediaType::Image,
            ],
            [
                'title' => 'Le jour du départ',
                'content_html' => '<p><strong>1.</strong> Lancez une machine de draps/serviettes (pas besoin d\'étendre, je m\'en occupe)<br><strong>2.</strong> Videz le frigo<br><strong>3.</strong> Fermez les fenêtres<br><strong>4.</strong> Remettez la clé dans la boîte à clés (code <strong>7741</strong>)<br><strong>5.</strong> Claquez la porte — elle se verrouille toute seule</p><p>C\'est tout. Bon voyage et merci pour votre séjour !</p>',
                'order' => 7,
                'action_type' => ActionType::PhotoUpload,
                'action_label' => 'Photo de la porte fermée',
                'media_path' => 'images/demo/step-7-checkout.png',
                'media_type' => MediaType::Image,
            ],
        ];

        foreach ($steps as $stepData) {
            Step::create(array_merge($stepData, ['guide_id' => $bellecour->id]));
        }
    }
}
