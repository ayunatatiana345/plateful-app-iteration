@php
    // Only show this toast popup for specific features (e.g., delete item / remove listing).
    // Other validation/errors should remain inline on their respective pages.

    $flashSuccess = session('success');
    $flashError = session('error');
    $flashOtpVerified = session('status') === 'otp-verified' ? 'Code verified. Your account is now active.' : null;

    // Gate: only allow toast popup when explicitly requested by the controller.
    // Usage example: return back()->with('toast', true)->with('success', 'Food item deleted.');
    $toastEnabled = (bool) session('toast', false);

    $flashValidationErrors = $toastEnabled && $errors->any() ? $errors->all() : [];

    $initialToasts = [];

    if ($toastEnabled) {
        if ($flashOtpVerified) {
            $initialToasts[] = ['type' => 'success', 'title' => 'Success', 'message' => $flashOtpVerified];
        }

        if ($flashSuccess) {
            $initialToasts[] = ['type' => 'success', 'title' => 'Success', 'message' => $flashSuccess];
        }

        if ($flashError) {
            $initialToasts[] = ['type' => 'error', 'title' => 'Error', 'message' => $flashError];
        }

        if (!empty($flashValidationErrors)) {
            $initialToasts[] = [
                'type' => 'error',
                'title' => 'Incomplete form',
                'message' => 'Please complete all required fields.',
                'details' => $flashValidationErrors,
            ];
        }
    }

    // Only show this toast popup for specific features.
    // Enable when controller sets: ->with('toast', true)
    $toastEnabled = (bool) session('toast', false);

    // Special: show a simple validation popup for inventory create/edit when validation fails.
    // This keeps the UI consistent with the green popup style near the navbar.
    $inventoryValidationPopup = $errors->any() && request()->routeIs('inventory.create', 'inventory.edit');
@endphp

@if ($inventoryValidationPopup)
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
        Please complete all required fields
    </div>
@endif

@if (session('status') === 'otp-verified')
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
        Code verified. Your account is now active.
    </div>
@endif

@if (session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif

{{-- Intentionally omit detailed validation errors to match the desired UI. --}}
