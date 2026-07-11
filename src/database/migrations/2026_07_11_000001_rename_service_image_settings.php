<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $renames = [
            'service_kulkas_image'     => 'service_laptop_image',
            'service_mesin_cuci_image' => 'service_hp_image',
        ];

        foreach ($renames as $old => $new) {
            $oldRow = DB::table('site_settings')->where('key', $old)->first();
            $newExists = DB::table('site_settings')->where('key', $new)->exists();

            if ($oldRow && $newExists) {
                // Both keys exist — keep new, migrate value if needed, remove old
                if (! DB::table('site_settings')->where('key', $new)->value('value') && $oldRow->value) {
                    DB::table('site_settings')->where('key', $new)->update(['value' => $oldRow->value]);
                }
                DB::table('site_settings')->where('key', $old)->delete();
            } elseif ($oldRow) {
                DB::table('site_settings')->where('key', $old)->update(['key' => $new]);
            } elseif (! $newExists) {
                DB::table('site_settings')->insert([
                    'key'        => $new,
                    'value'      => null,
                    'type'       => 'image',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $renames = [
            'service_laptop_image' => 'service_kulkas_image',
            'service_hp_image'     => 'service_mesin_cuci_image',
        ];

        foreach ($renames as $old => $new) {
            if (DB::table('site_settings')->where('key', $old)->exists()) {
                DB::table('site_settings')->where('key', $old)->update(['key' => $new]);
            }
        }
    }
};
