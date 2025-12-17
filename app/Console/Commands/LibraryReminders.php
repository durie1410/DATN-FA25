<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LibraryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due-soon and overdue reminders to users';

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
        $dueSoonDays = (int) config('library.reminder_due_soon_days', 1);
        $overdueDays = (int) config('library.reminder_overdue_days', 0);

        // Due soon: picked_up with due_date = today + N
        $dueSoonDate = now()->addDays($dueSoonDays)->startOfDay();
        $loansDueSoon = \DB::table('loans')
            ->where('status', 'picked_up')
            ->whereDate('due_date', $dueSoonDate)
            ->get(['id','user_id','due_date']);
        foreach ($loansDueSoon as $loan) {
            $email = optional(\DB::table('users')->where('id', $loan->user_id)->first())->email ?? null;
            \App\Services\Notifier::sendEmail($email, 'Books Due Soon', 'Your loan #'.$loan->id.' is due on '.$loan->due_date);
        }

        // Overdue: overdue status or picked_up and due_date < today - N
        $overdueDate = now()->subDays($overdueDays)->startOfDay();
        $overdues = \DB::table('loans')
            ->where(function($q) use ($overdueDate) {
                $q->where('status', 'overdue')
                  ->orWhere(function($q2) use ($overdueDate) {
                      $q2->where('status', 'picked_up')->whereDate('due_date', '<', $overdueDate);
                  });
            })
            ->get(['id','user_id','due_date']);
        foreach ($overdues as $loan) {
            $email = optional(\DB::table('users')->where('id', $loan->user_id)->first())->email ?? null;
            \App\Services\Notifier::sendEmail($email, 'Books Overdue', 'Your loan #'.$loan->id.' is overdue. Please return soon.');
        }

        $this->info('Reminders sent: dueSoon='.count($loansDueSoon).', overdue='.count($overdues));
        return 0;
    }
}
