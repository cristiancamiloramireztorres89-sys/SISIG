@props(['title' => 'Panel Editorial'])

<x-sisig::layouts.admin :title="$title" {{ $attributes }}>
    {{ $slot }}
</x-sisig::layouts.admin>