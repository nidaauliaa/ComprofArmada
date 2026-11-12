<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Nomor di menu Kontak dan di form Plant HQ dijadikan SATU data: plants.phones (+ plants.phone_names untuk label).
 * Isi tabel sales_contacts lama digabungkan ke Plant HQ (nomor yang sama tidak diduplikasi).
 * Tabel sales_contacts dibiarkan apa adanya sebagai cadangan, tapi tidak dipakai lagi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->json('phone_names')->nullable()->after('phones'); // label per nomor, urutan sama dengan phones
        });

        if (! Schema::hasTable('sales_contacts')) {
            return;
        }

        $hq = DB::table('plants')->where('is_hq', true)->orderBy('sort_order')->orderBy('id')->first()
            ?? DB::table('plants')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->first();

        if (! $hq) {
            return;
        }

        $norm = function (string $p): string {
            $d = preg_replace('/\D/', '', $p);
            if (str_starts_with($d, '62')) { $d = '0'.substr($d, 2); }
            return $d;
        };

        $phones = array_values(array_filter((array) json_decode((string) $hq->phones, true), fn ($v) => trim((string) $v) !== ''));
        if (! $phones && $hq->phone) { $phones = [trim($hq->phone)]; }
        $names = array_fill(0, count($phones), '');

        foreach (DB::table('sales_contacts')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get() as $s) {
            $found = null;
            foreach ($phones as $i => $p) {
                if ($norm($p) === $norm($s->phone)) { $found = $i; break; }
            }
            if ($found === null) {
                $phones[] = trim($s->phone);
                $names[]  = trim($s->name);
            } elseif ($names[$found] === '') {
                $names[$found] = trim($s->name);
            }
        }

        DB::table('plants')->where('id', $hq->id)->update([
            'phones'      => json_encode($phones, JSON_UNESCAPED_UNICODE),
            'phone_names' => json_encode($names, JSON_UNESCAPED_UNICODE),
            'phone'       => $phones[0] ?? null,
        ]);
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn('phone_names');
        });
    }
};
