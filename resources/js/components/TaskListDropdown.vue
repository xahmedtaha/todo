<script setup>
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {ChevronDownIcon, PlusIcon} from "@heroicons/vue/20/solid";
import {TrashIcon, PencilSquareIcon} from "@heroicons/vue/24/outline";
import TaskListModal from "./TaskListModal.vue";
import {computed, ref} from "vue";

const props = defineProps({
    lists: Array,
    currentList: Number,
    loading: Boolean,
})
defineEmits(['changeList', 'saveList', 'deleteList'])

const currentListData = computed(() => props.lists.find((item) => item.id === props.currentList))

const modalListData = ref({id: null, title: null})

const openCreateModal = () => {
    modalListData.value = {id: null, title: null}
    modalOpen.value = true
}
const openEditModal = () => {
    let data = props.lists.find((list) => list.id === props.currentList)
    if(data !== -1) {
        modalListData.value = {id: data.id, title: data.title}
        modalOpen.value = true
    }
}
const save = (data) => {

}

const modalOpen = ref(false)
</script>

<template>
    <TaskListModal @save="(data) => $emit('saveList', data)" :list="modalListData" @close="modalOpen = false" :open="modalOpen" />
    <div class="flex gap-x-6 items-center">
        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton :disabled="loading" class="disabled:opacity-25 inline-flex w-full justify-center items-center gap-x-1.5">
                    <span class="text-xl font-bold leading-9 tracking-tight text-gray-900">{{currentListData?.title}}</span>
                    <ChevronDownIcon class="-mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
                </MenuButton>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute left-0 z-10 mt-2 w-40 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                        <MenuItem v-slot="{ active }" v-for="list in lists" :key="list.id">
                            <button @click="$emit('changeList', list.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm', currentList === list.id ? 'font-bold' : '']">{{ list.title }}</button>
                        </MenuItem>
                    </div>
                    <div class="py-1">
                        <MenuItem v-slot="{ active }">
                            <button @click="openCreateModal" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'w-full text-left group flex items-center px-4 py-2 text-sm']">
                                <PlusIcon class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                                Add list
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>

        <div class="flex items-center gap-x-3">
            <button @click="openEditModal" type="button" class="disabled:opacity-25 rounded-full text-gray-600 shadow-sm hover:text-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                <PencilSquareIcon class="h-5 w-5" aria-hidden="true" />
            </button>
            <button :disabled="lists.length <= 1" @click="$emit('deleteList')" type="button" class="disabled:opacity-25 rounded-full text-gray-600 shadow-sm hover:text-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                <TrashIcon class="h-5 w-5" aria-hidden="true" />
            </button>
        </div>
    </div>
</template>
