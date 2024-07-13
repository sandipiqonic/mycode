<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('cache:clear');
        Schema::disableForeignKeyConstraints();
        $file = new Filesystem;
        $file->cleanDirectory('storage/app/public');
        $this->call(AuthTableSeeder::class);
        $this->call(SettingSeeder::class);
        // $this->call(\Modules\Currency\database\seeders\CurrencyDatabaseSeeder::class);
        $this->call(\Modules\Constant\database\seeders\ConstantDatabaseSeeder::class);
        $this->call(\Modules\Genres\database\seeders\GenresDatabaseSeeder::class);
        $this->call(\Modules\CastCrew\database\seeders\CastCrewDatabaseSeeder::class);
        $this->call(\Modules\Entertainment\database\seeders\EntertainmentDatabaseSeeder::class);
        $this->call(\Modules\Season\database\seeders\SeasonDatabaseSeeder::class);
        $this->call(\Modules\Episode\database\seeders\EpisodeDatabaseSeeder::class);
        $this->call(\Modules\Page\database\seeders\PageDatabaseSeeder::class);
        $this->call(\Modules\NotificationTemplate\database\seeders\NotificationTemplateSeeder::class);
        Schema::enableForeignKeyConstraints();
        \Artisan::call('cache:clear');
    }
}

