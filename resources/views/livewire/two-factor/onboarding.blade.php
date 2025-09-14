<div>
    <flux:modal wire:model="showModal" class="max-w-2xl" :dismissible="false">
        <div class="p-6">
            @if($step === 'intro')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Secure Your Account') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Two-factor authentication is now required for all accounts') }}
                        </p>
                    </div>

                    <div class="flex justify-center">
                        <div class="flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600">
                            <flux:icon.shield-check class="text-white size-16" />
                        </div>
                    </div>

                    <div class="space-y-3 text-center">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('Enhanced Security Required') }}
                        </h3>
                        <p class="text-gray-600">
                            {{ __('To protect your account, we now require two-factor authentication for all users.') }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <flux:icon.device-phone-mobile class="size-5 text-blue-500 mt-0.5" />
                            <div>
                                <p class="font-medium text-gray-900">{{ __('Use Your Phone') }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ __('You\'ll need an authenticator app like Google Authenticator or Authy') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <flux:icon.clock class="size-5 text-green-500 mt-0.5" />
                            <div>
                                <p class="font-medium text-gray-900">{{ __('Quick Setup') }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ __('Takes less than 2 minutes to complete') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <flux:icon.key class="size-5 text-purple-500 mt-0.5" />
                            <div>
                                <p class="font-medium text-gray-900">{{ __('Recovery Codes') }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ __('You\'ll receive backup codes in case you lose your device') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <flux:button variant="primary" icon="arrow-right" wire:click="startSetup" class="w-full sm:w-auto">
                            {{ __('Start Setup') }}
                        </flux:button>
                    </div>
                </div>

            @elseif($step === 'setup')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Set Up Authenticator App') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Step 1 of 2: Connect your authenticator app') }}
                        </p>
                    </div>

                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="flex">
                            <flux:icon.information-circle class="text-blue-400 size-5" />
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    {{ __('Download an Authenticator App') }}
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>{{ __('Popular options include:') }}</p>
                                    <ul class="mt-1 list-disc list-inside">
                                        <li>Google Authenticator</li>
                                        <li>Microsoft Authenticator</li>
                                        <li>Authy</li>
                                        <li>1Password</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="mb-4 text-sm text-gray-600">
                            {{ __('Scan this QR code with your authenticator app') }}
                        </p>
                        <div class="flex justify-center p-6 bg-white border-2 border-gray-200 rounded-lg">
                            {!! $qrCodeSvg !!}
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
                            class="text-xl tracking-widest text-center"
                            wire:keydown.enter="confirmTwoFactor"
                        />
                        <flux:error name="confirmationCode" />
                        <flux:description>
                            {{ __('Enter the 6-digit code from your authenticator app') }}
                        </flux:description>
                    </flux:field>

                    <div class="flex justify-end">
                        <flux:button variant="primary" icon="check" wire:click="confirmTwoFactor" wire:loading.attr="disabled">
                            {{ __('Verify & Continue') }}
                        </flux:button>
                    </div>
                </div>

            @elseif($step === 'recovery')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Two-Factor Authentication Enabled!') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Your account is now protected. Here are your recovery codes.') }}
                        </p>
                    </div>

                    <div class="p-4 border rounded-lg bg-green-50 border-green-200">
                        <div class="flex">
                            <flux:icon.check-circle class="flex-shrink-0 size-5 text-green-400" />
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">{{ __('Setup Complete') }}</h3>
                                <p class="mt-1 text-sm text-green-700">
                                    {{ __('Two-factor authentication is now active on your account.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border rounded-lg bg-amber-50 border-amber-200">
                        <div class="flex">
                            <flux:icon.exclamation-triangle class="flex-shrink-0 size-5 text-amber-400" />
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">{{ __('Recovery Codes (Optional)') }}</h3>
                                <p class="mt-1 text-sm text-amber-700">
                                    {{ __('Save these codes if you want backup access. Each code can only be used once. You can regenerate new codes anytime from your profile.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50">
                        <div class="grid grid-cols-2 gap-3 font-mono text-sm">
                            @foreach($recoveryCodes as $code)
                                <div class="p-3 text-center bg-white border border-gray-300 rounded">
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
                        <flux:button
                            variant="primary"
                            icon="check-circle"
                            wire:click="completeSetup"
                            wire:loading.attr="disabled"
                        >
                            {{ __('Done') }}
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>

    <div x-data @notify.window="
        if ($event.detail.message) {
            $dispatch('flux:toast', {
                text: $event.detail.message,
                variant: 'success'
            });
        }
    "></div>
</div>
