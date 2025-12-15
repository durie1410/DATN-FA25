<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LibraryHousekeeping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:housekeeping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run daily/hourly operational tasks (mark overdue, future no-show cleanup)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();

        // Mark overdue loans: picked_up and due_date < now -> overdue
        $affected = \DB::table('loans')
            ->where('status', 'picked_up')
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now)
            ->update(['status' => 'overdue', 'updated_at' => $now]);

        $this->info("Loans marked overdue: {$affected}");

        // Auto no-show for seat reservations past hold window
        $nowStr = $now->toDateTimeString();
        $noShow = \DB::table('seat_reservations')
            ->where('status', 'pending')
            ->whereNotNull('hold_until')
            ->where('hold_until', '<', $nowStr)
            ->update(['status' => 'no_show', 'updated_at' => $now]);
        $this->info("Seat reservations marked no_show: {$noShow}");

        return 0;
    }
}
