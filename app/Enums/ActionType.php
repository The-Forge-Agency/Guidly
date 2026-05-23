<?php

namespace App\Enums;

enum ActionType: string
{
    case Acknowledge = 'acknowledge';
    case PhotoUpload = 'photo_upload';
    case Checkbox = 'checkbox';
    case None = 'none';

    public function label(): string
    {
        return match ($this) {
            self::Acknowledge => "J'ai compris",
            self::PhotoUpload => 'Envoyer une photo',
            self::Checkbox => 'Cocher',
            self::None => 'Aucune action',
        };
    }
}
