<?php

namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA;
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function generateQrCodeSvg(User $user): string
    {
        $companyName = config('app.name');
        $companyEmail = $user->email;
        $secret = $user->two_factor_secret;

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secret
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(250, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        );

        return $writer->writeString($qrCodeUrl);
    }

    public function verify(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    public function isValidCode(User $user, string $code): bool
    {
        if (! $user->two_factor_secret) {
            return false;
        }

        return $this->verify($user->two_factor_secret, $code);
    }

    public function isValidRecoveryCode(User $user, string $code): bool
    {
        return $user->useTwoFactorRecoveryCode($code);
    }

    public function enableTwoFactor(User $user): void
    {
        $user->two_factor_secret = $this->generateSecretKey();
        $user->generateTwoFactorRecoveryCodes();
        $user->save();
    }

    public function confirmTwoFactor(User $user, string $code): bool
    {
        if (! $this->isValidCode($user, $code)) {
            return false;
        }

        $user->two_factor_confirmed_at = now();
        $user->save();

        return true;
    }

    public function disableTwoFactor(User $user): void
    {
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();
    }
}
