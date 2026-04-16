<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-xl font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 bg-[var(--primary)] text-[var(--text)] hover:bg-[var(--primary-dark)] focus:bg-[var(--primary-dark)] active:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-dark)] focus:ring-offset-2 focus:ring-offset-white ring-1 ring-black/5']) }}>
    {{ $slot }}
</button>
