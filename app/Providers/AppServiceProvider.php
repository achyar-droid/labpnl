<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Peminjaman;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * Paksa HTTPS kalau bukan local (untuk Cloudflare / hosting)
         */
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        /**
         * Share badge pengembalian ke semua view
         */
        View::composer('*', function ($view) {

            $lab = request()->route('lab'); // ambil parameter lab dari URL

            if ($lab) {
                $jumlahBelumDikembalikan = Peminjaman::where('lab', $lab)
                    ->where('status', 'dipinjam')
                    ->count();
            } else {
                $jumlahBelumDikembalikan = 0;
            }

            // SELALU kirim variabel ke view (supaya tidak undefined)
            $view->with('badgePengembalian', $jumlahBelumDikembalikan);
        });
    }
}
