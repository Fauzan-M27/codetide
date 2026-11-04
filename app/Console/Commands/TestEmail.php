<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email? : The email address to send test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        
        $this->info('Testing email configuration...');
        $this->info('Sending test email to: ' . $email);
        
        try {
            Mail::raw('This is a test email from CodeTide. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email from CodeTide');
            });
            
            $this->info('✅ Email sent successfully!');
            $this->info('');
            $this->info('If using Mailtrap:');
            $this->info('  → Check your Mailtrap inbox at https://mailtrap.io');
            $this->info('');
            $this->info('If using Gmail:');
            $this->info('  → Check your inbox/spam folder');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->info('');
            $this->info('Troubleshooting:');
            $this->info('  1. Check .env mail configuration');
            $this->info('  2. Run: php artisan config:cache');
            $this->info('  3. Check logs: storage/logs/laravel.log');
            
            return Command::FAILURE;
        }
    }
}
