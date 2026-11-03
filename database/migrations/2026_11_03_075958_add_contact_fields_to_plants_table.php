<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->json('phones')->nullable()->after('phone');                 // daftar nomor telepon
            $table->string('email', 150)->nullable()->after('phones');
            $table->string('working_hours')->nullable()->after('email');        // jam kerja (ID)
            $table->string('working_hours_en')->nullable()->after('working_hours');
            $table->string('working_hours_zh')->nullable()->after('working_hours_en');
            $table->boolean('is_hq')->default(false)->after('is_active');       // kantor pusat
        });

        // Isi awal: nomor lama jadi nomor pertama; HQ diisi dengan data yang tadinya hardcode di halaman /kontak.
        $rows = DB::table('plants')->orderBy('sort_order')->orderBy('id')->get();
        $hq = $rows->first(fn ($r) => stripos((string) $r->name, 'HQ') !== false) ?? $rows->first();

        foreach ($rows as $r) {
            $update = ['phones' => json_encode($r->phone ? [$r->phone] : [], JSON_UNESCAPED_UNICODE)];

            if ($hq && $r->id === $hq->id) {
                $update = [
                    'phones'           => json_encode(['(0293) 363591', '(0293) 364371']),
                    'email'            => 'marketing@newarmada.co.id',
                    'working_hours'    => 'Senin–Jumat, 08.00–16.00 WIB',
                    'working_hours_en' => 'Monday–Friday, 08:00–16:00 WIB',
                    'working_hours_zh' => '周一至周五，08:00–16:00（WIB）',
                    'is_hq'            => true,
                ];
            }

            DB::table('plants')->where('id', $r->id)->update($update);
        }
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn(['phones', 'email', 'working_hours', 'working_hours_en', 'working_hours_zh', 'is_hq']);
        });
    }
};
