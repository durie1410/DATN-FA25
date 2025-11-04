<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CleanFakeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:fake-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa dữ liệu fake, chỉ giữ lại 10 id đầu tiên';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keepIds = User::orderBy('id', 'asc')
            ->limit(10)
            ->pluck('id');

        User::whereNotIn('id', $keepIds)->delete();

        $this->info('✅ Đã xóa dữ liệu fake, giữ lại 10 id đầu tiên.');
    }
}
