<script setup>
import {PencilSquareIcon, TrashIcon, ArrowPathIcon} from "@heroicons/vue/24/outline";
import {computed} from "vue";

const props = defineProps({
    task: Object
})
defineEmits(['edit', 'delete', 'restore'])

const isDue = computed(() => {
    if(props.task.due_date) {
        return (new Date(props.task.due_date)) > Date.now()
    }
    return false
})
const dueDate = computed(() => {
    if(props.task.due_date) {
        return (new Date(props.task.due_date)).toDateString()
    }
    return null
})
</script>

<template>
    <div class="flex justify-between gap-x-6 items-center">
        <div class="min-w-0 flex-auto">
            <p class="text-sm font-semibold leading-6 text-gray-900">
                {{ task.title }} <span class="font-light italic text-gray-700 text-xs" :class="{isDue: 'text-red-600'}" v-if="task.due_date"> - Due {{dueDate}} </span>
            </p>
            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ task.description }}</p>
        </div>
        <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
            <div class="flex items-center gap-x-3">
                <button @click="$emit('edit', task.id)" type="button" class="disabled:opacity-25 rounded-full text-gray-600 shadow-sm hover:text-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                    <PencilSquareIcon class="h-5 w-5" aria-hidden="true" />
                </button>
                <button v-if="task.deleted_at" @click="$emit('restore', task.id)" type="button" class="disabled:opacity-25 rounded-full text-gray-600 shadow-sm hover:text-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                    <ArrowPathIcon class="h-5 w-5" aria-hidden="true" />
                </button>
                <button v-else @click="$emit('delete', task.id)" type="button" class="disabled:opacity-25 rounded-full text-gray-600 shadow-sm hover:text-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                    <TrashIcon class="h-5 w-5" aria-hidden="true" />
                </button>
            </div>
        </div>
    </div>
</template>
