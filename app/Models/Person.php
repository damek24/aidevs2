<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $imie
 * @property string $nazwisko
 * @property int $wiek
 * @property string $o_mnie
 * @property string $ulubiona_postac_z_kapitana_bomby
 * @property string $ulubiony_serial
 * @property string $ulubiony_film
 * @property string $ulubiony_kolor
 */
class Person extends Model
{
    protected $fillable = [
        'imie',
        'nazwisko',
        'wiek',
        'o_mnie',
        'ulubiona_postac_z_kapitana_bomby',
        'ulubiony_film',
        'ulubiony_kolor',
        'ulubiony_serial',
    ];

    public function context(): string
    {
        $context = [
            'imię i nazwisko: '. $this->imie . ' ' . $this->nazwisko,
            'O mnie: '. $this->o_mnie,
            'ulubiony kolor:'. $this->ulubiony_kolor,
            'ulubiony serial: '. $this->ulubiony_serial,
            'ulubiony film: '. $this->ulubiony_film,
            'kapitan bomba: ' . $this->ulubiona_postac_z_kapitana_bomby
        ];

        return implode("\n", $context);
    }
}
