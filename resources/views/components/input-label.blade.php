@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-slate-900 dark:text-slate-100']) }}>
    {{ $value ?? $slot }}
</label>
