@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Expenses Tracker" {{ $attributes->class(['text-black dark:text-white']) }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Expenses Tracker" {{ $attributes->class(['text-black dark:text-white']) }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
