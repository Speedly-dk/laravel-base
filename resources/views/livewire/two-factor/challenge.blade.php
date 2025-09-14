<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{ __('Two-Factor Authentication') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('Please enter your authentication code to continue') }}
            </p>
        </div>

        <flux:card class="mt-8">
            <div class="p-6">
                <form wire:submit="verify" class="space-y-6">
                    @if(!$useRecoveryCode)
                        <flux:field>
                            <flux:label>{{ __('Authentication Code') }}</flux:label>
                            <flux:input
                                wire:model="code"
                                type="text"
                                placeholder="000000"
                                maxlength="6"
                                autocomplete="one-time-code"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                autofocus
                                class="text-center text-2xl tracking-widest"
                            />
                            <flux:error name="code" />
                            <flux:description>
                                {{ __('Enter the 6-digit code from your authenticator app') }}
                            </flux:description>
                        </flux:field>
                    @else
                        <flux:field>
                            <flux:label>{{ __('Recovery Code') }}</flux:label>
                            <flux:input
                                wire:model="recoveryCode"
                                type="text"
                                placeholder="{{ __('xxxxx-xxxxx') }}"
                                autofocus
                                class="font-mono"
                            />
                            <flux:error name="recoveryCode" />
                            <flux:description>
                                {{ __('Enter one of your recovery codes') }}
                            </flux:description>
                        </flux:field>
                    @endif

                    <div class="flex items-center justify-between">
                        <flux:button type="button" variant="ghost" size="sm" wire:click="toggleRecoveryCode">
                            @if(!$useRecoveryCode)
                                {{ __('Use recovery code') }}
                            @else
                                {{ __('Use authentication code') }}
                            @endif
                        </flux:button>

                        <flux:button type="button" variant="ghost" size="sm" wire:click="cancel">
                            {{ __('Cancel') }}
                        </flux:button>
                    </div>

                    <flux:button type="submit" variant="primary" icon="lock-closed" class="w-full">
                        {{ __('Verify') }}
                    </flux:button>
                </form>
            </div>
        </flux:card>

        <div class="mt-6" x-data="{
            showHelp: false,
            autoFocus() {
                setTimeout(() => {
                    const input = document.querySelector('input[autofocus]');
                    if (input) {
                        input.focus();
                        input.select();
                    }
                }, 100);
            }
        }" x-init="autoFocus()" @rate-limited.window="showHelp = true">
            <flux:modal x-show="showHelp" @close="showHelp = false" class="max-w-md">
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Need Help?') }}</h3>
                    </div>

                    <p class="text-sm text-gray-600">
                        {{ __('If you\'re having trouble with your authenticator app:') }}
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                        <li>{{ __('Make sure your device\'s time is synced correctly') }}</li>
                        <li>{{ __('Try using a recovery code instead') }}</li>
                        <li>{{ __('Contact support if you\'ve lost access to your device') }}</li>
                    </ul>

                    <div class="flex justify-end">
                        <flux:button variant="primary" @click="showHelp = false">
                            {{ __('Got it') }}
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
        </div>
    </div>
</div>