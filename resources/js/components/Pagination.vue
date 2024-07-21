<script setup>
defineProps({
    currentItemsCount: Number,
    perPage: Number,
    currentPage: Number,
    total: Number,
    next: String,
    prev: String,
})
defineEmits(['prev', 'next'])
</script>
<template>
    <nav class="flex items-center justify-between bg-white" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700">
                Showing
                {{ ' ' }}
                <span class="font-medium">{{ Math.min(((currentPage - 1) * perPage) + 1, total) }}</span>
                {{ ' ' }}
                to
                {{ ' ' }}
                <span class="font-medium">{{ ((currentPage - 1) * perPage) + currentItemsCount }}</span>
                {{ ' ' }}
                of
                {{ ' ' }}
                <span class="font-medium">{{ total }}</span>
                {{ ' ' }}
                results
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end">
            <button :disabled="!prev" @click="$emit('prev')" class="disabled:opacity-25 disabled:cursor-pointer relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</button>
            <button :disabled="!next" @click="$emit('next')" class="disabled:opacity-25 disabled:cursor-pointer relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</button>
        </div>
    </nav>
</template>
