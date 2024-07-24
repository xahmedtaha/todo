<script setup>
import {ref, watchEffect} from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
    open: Boolean,
    task: {
        type: Object,
        default: {
            id: null,
            title: '',
            description: '',
            status: 'pending',
            due_date: null,
        }
    },
})
const emit = defineEmits(['close', 'save'])

const loading = ref(false)

const currentTask = ref({
    id: props.task?.id,
    title: props.task?.title,
    description: props.task?.description,
    status: props.task?.status,
    due_date: props.task?.due_date,
})
watchEffect((isOpen) => {
    if (isOpen) currentTask.value = {
        id: props.task?.id,
        title: props.task?.title,
        description: props.task?.description,
        status: props.task?.status,
        due_date: props.task?.due_date,
    }
})

const submit = () => {
    if (currentTask.value?.title) {
        emit('save', currentTask.value)
        emit('close')
    }
}
</script>

<template>
    <teleport to="body">
        <TransitionRoot as="template" :show="open">
            <Dialog class="relative z-10" @close="$emit('close')">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <form @submit.prevent="submit">
                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm">
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                <div class="mt-2">
                                                    <label for="task-title" class="sr-only">Title</label>
                                                    <input v-model="currentTask.title" :disabled="loading" type="text" name="task-title" id="task-title" class="focus:ring-0 p-0 ring-0 shadow-none border-none block text-gray-700 font-semibold w-full placeholder:text-gray-400 sm:leading-6 text-lg" autofocus placeholder="Title..." />
                                                </div>
                                                <div class="mt-2">
                                                    <label for="task-description" class="sr-only">Description</label>
                                                    <input v-model="currentTask.description" :disabled="loading" type="text" name="task-description" id="task-description" class="focus:ring-0 p-0 ring-0 shadow-none border-none block text-gray-700 w-full placeholder:text-gray-400 sm:leading-6 text-md" placeholder="Description..." />
                                                </div>
                                                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                                                    <div class="flex-1">
                                                        <label for="task-due-date" class="sr-only">Due Date</label>
                                                        <input v-model="currentTask.due_date" :disabled="loading" type="date" name="task-due-date" id="task-due-date" class="focus:ring-0 p-0 ring-0 shadow-none border-none block text-gray-700 w-full placeholder:text-gray-400 sm:leading-6 text-sm" placeholder="Due Date..." />
                                                    </div>
                                                    <div class="flex-1">
                                                        <label for="task-status" class="sr-only">Status</label>
                                                        <select v-model="currentTask.status" :disabled="loading" name="task-status" id="task-status" class="focus:ring-0 p-0 ring-0 shadow-none border-none block text-gray-700 w-full placeholder:text-gray-400 sm:leading-6 text-sm">
                                                            <option value="pending">Pending</option>
                                                            <option value="completed">Completed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <button :disabled="loading || !currentTask?.title" type="submit" class="disabled:opacity-25 inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">{{currentTask?.id ? "Save" : "Create"}}</button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </form>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
