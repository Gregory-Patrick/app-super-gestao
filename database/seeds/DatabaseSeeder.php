<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        #Incluindo uma chamada de uma seeder criada:
        $this->call(FornecedorSeeder::class);
        $this->call(SiteContatoSeeder::class);

    }
}
