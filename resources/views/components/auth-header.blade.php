@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center gap-1 mb-2">
    <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
    <p class="text-sm text-gray-500">{{ $description }}</p>
</div>
