<?php

namespace Database\Seeders;

use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class HelpArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creating = HelpCategory::create([
            'name'          => 'Creating Events',
            'icon'          => 'fa-calendar-plus',
            'description'   => 'How to create your own event'
        ]);

        $creating->articles()->save(new HelpArticle([
            'title'         => 'Creating Free Events',
            'body'          => "Creating free events is easy, and it's also free to do, since we don't charge!",
            'author_id'     => User::where('email', '=', 'test@example.com')->first()->id
        ]));
    }
}
