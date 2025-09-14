<div class="space-y-6">
    @if(!$this->user->hasTwoFactorEnabled())
        <flux:card>
            <div class="p-6 space-y-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Two-Factor Authentication') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Add an extra layer of security to your account using two-factor authentication.') }}
                    </p>
                </div>

                <p class="text-sm text-zinc-600">
                    {{ __('When two-factor authentication is enabled, you will be prompted for a secure, random code from your authenticator app during login.') }}
                </p>

                <div class="pt-4">
                    <flux:button variant="primary" icon="shield-check" wire:click="enableTwoFactor">
                        {{ __('Enable Two-Factor Authentication') }}
                    </flux:button>
                </div>
            </div>
        </flux:card>
    @else
        <flux:card>
            <div class="p-6 space-y-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Two-Factor Authentication') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Two-factor authentication is currently enabled for your account.') }}
                    </p>
                </div>

                <flux:badge variant="success" size="lg" icon="shield-check">
                    {{ __('Active') }}
                </flux:badge>

                <p class="text-sm text-zinc-600">
                    {{ __('Your account is protected with two-factor authentication.') }}
                </p>

                <div class="flex gap-3 pt-4">
                    <flux:button variant="ghost" icon="arrow-path" wire:click="regenerateRecoveryCodes">
                        {{ __('Regenerate Recovery Codes') }}
                    </flux:button>

                    <flux:button variant="danger" icon="shield-exclamation" wire:click="$set('showDisableModal', true)">
                        {{ __('Disable 2FA') }}
                    </flux:button>
                </div>
            </div>
        </flux:card>
    @endif

    <flux:modal wire:model="showQrCode" class="max-w-lg">
        <div class="p-6 space-y-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ __('Set Up Authenticator App') }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Scan this QR code with your authenticator app to get started.') }}
                </p>
            </div>

            <div class="flex justify-center p-6 bg-white rounded-lg">
                {!! $qrCodeSvg !!}
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex">
                    <flux:icon.information-circle class="size-5 text-blue-400 flex-shrink-0" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Use an Authenticator App') }}</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            {{ __('Scan the QR code above with apps like Google Authenticator, Authy, or 1Password.') }}
                        </p>
                    </div>
                </div>
            </div>

            <flux:field>
                <flux:label>{{ __('Verification Code') }}</flux:label>
                <flux:input
                    wire:model="confirmationCode"
                    placeholder="000000"
                    maxlength="6"
                    autocomplete="one-time-code"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    wire:keydown.enter="confirmTwoFactor"
                />
                <flux:error name="confirmationCode" />
                <flux:description>
                    {{ __('Enter the 6-digit code from your authenticator app') }}
                </flux:description>
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="cancelSetup">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button variant="primary" icon="check" wire:click="confirmTwoFactor" wire:loading.attr="disabled">
                    {{ __('Verify & Enable') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="showRecoveryCodes" class="max-w-lg">
        <div class="p-6 space-y-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ __('Recovery Codes') }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Store these recovery codes in a secure location.') }}
                </p>
            </div>

            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex">
                    <flux:icon.exclamation-triangle class="size-5 text-amber-400 flex-shrink-0" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">{{ __('Important') }}</h3>
                        <p class="mt-1 text-sm text-amber-700">
                            {{ __('Each code can only be used once. Store them securely and never share them.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-zinc-50 rounded-lg">
                <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                    @foreach($recoveryCodes as $code)
                        <div class="p-2 bg-white rounded border border-zinc-200">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-data="{
                downloadCodes() {
                    const codes = @js($recoveryCodes).join('\n');
                    const blob = new Blob([codes], { type: 'text/plain' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'recovery-codes-' + new Date().toISOString().split('T')[0] + '.txt';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }
            }">
                <flux:button variant="ghost" icon="arrow-down-tray" @click="downloadCodes" class="w-full">
                    {{ __('Download Recovery Codes') }}
                </flux:button>
            </div>

            <div class="flex justify-end">
                <flux:button variant="primary" wire:click="$set('showRecoveryCodes', false)">
                    {{ __('I Have Saved My Codes') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="showDisableModal" class="max-w-md">
        <div class="p-6 space-y-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ __('Disable Two-Factor Authentication') }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Are you sure you want to disable two-factor authentication?') }}
                </p>
            </div>

            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <flux:icon.exclamation-triangle class="size-5 text-red-400 flex-shrink-0" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ __('Security Warning') }}</h3>
                        <p class="mt-1 text-sm text-red-700">
                            {{ __('Disabling 2FA will make your account less secure.') }}
                        </p>
                    </div>
                </div>
            </div>

            <flux:field>
                <flux:label>{{ __('Confirm Password') }}</flux:label>
                <flux:input
                    type="password"
                    wire:model="disablePassword"
                    placeholder="{{ __('Enter your password') }}"
                    wire:keydown.enter="confirmDisableTwoFactor"
                />
                <flux:error name="disablePassword" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="$set('showDisableModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button variant="danger" icon="shield-exclamation" wire:click="confirmDisableTwoFactor" wire:loading.attr="disabled">
                    {{ __('Disable 2FA') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>