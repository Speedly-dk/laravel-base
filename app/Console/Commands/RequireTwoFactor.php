<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RequireTwoFactor extends Command
{
    protected $signature = 'user:require-2fa {email}';

    protected $description = 'Require two-factor authentication for a specific user';

    public function handle(): void
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found.");

            return;
        }

        $user->two_factor_required = true;

        // Reset 2FA if already enabled to trigger onboarding
        if ($user->hasTwoFactorEnabled()) {
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->two_factor_confirmed_at = null;
            $this->info('Existing 2FA settings cleared.');
        }

        $user->save();

        $this->info("✓ Two-factor authentication is now required for {$user->email}");
        $this->info('→ The onboarding modal will appear when they visit the profile page or any protected route.');
    }
}
