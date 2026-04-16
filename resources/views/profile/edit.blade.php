<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Settings &amp; Privacy</h1>
                <p class="text-sm text-gray-600">Manage your profile, security, and privacy preferences.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Profile</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Security</h3>
                    </div>
                    <div class="px-6 py-5">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Privacy Settings</h3>
                    </div>

                    <form action="{{ route('profile.privacy.update') }}" method="POST" class="px-6 py-5 space-y-5">
                        @csrf
                        @method('PATCH')

                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900">Two-Factor Authentication</div>
                                <div class="text-sm text-gray-600">Extra login security</div>
                            </div>
                            <input type="checkbox" name="privacy_two_factor_enabled" value="1"
                                @checked((bool) (auth()->user()->privacy_two_factor_enabled ?? false))
                                class="h-5 w-5 rounded border-gray-200 text-[var(--primary-dark)] focus:ring-[var(--primary)]">
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900">Food Listing Visibility</div>
                                <div class="text-sm text-gray-600">Who sees your donations</div>
                            </div>
                            <select name="privacy_food_listing_visibility"
                                class="rounded-xl border-gray-200 bg-white text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                                <option value="public" @selected((auth()->user()->privacy_food_listing_visibility ?? 'public') === 'public')>Public</option>
                                <option value="private" @selected((auth()->user()->privacy_food_listing_visibility ?? 'public') === 'private')>Private</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900">Expiry Notifications</div>
                                <div class="text-sm text-gray-600">Alerts before food expires</div>
                            </div>
                            <input type="checkbox" name="privacy_expiry_notifications" value="1"
                                @checked((bool) (auth()->user()->privacy_expiry_notifications ?? true))
                                class="h-5 w-5 rounded border-gray-200 text-[var(--primary-dark)] focus:ring-[var(--primary)]">
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900">Meal Plan Reminders</div>
                                <div class="text-sm text-gray-600">Daily cooking reminders</div>
                            </div>
                            <input type="checkbox" name="privacy_meal_plan_reminders" value="1"
                                @checked((bool) (auth()->user()->privacy_meal_plan_reminders ?? true))
                                class="h-5 w-5 rounded border-gray-200 text-[var(--primary-dark)] focus:ring-[var(--primary)]">
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="font-medium text-gray-900">Donation Updates</div>
                                <div class="text-sm text-gray-600">Notify when items are claimed</div>
                            </div>
                            <input type="checkbox" name="privacy_donation_updates" value="1"
                                @checked((bool) (auth()->user()->privacy_donation_updates ?? true))
                                class="h-5 w-5 rounded border-gray-200 text-[var(--primary-dark)] focus:ring-[var(--primary)]">
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="inline-flex items-center rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
