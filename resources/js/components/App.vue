<script setup>
import {onMounted, ref} from "vue";
import UserDropdown from "./UserDropdown.vue";
import Task from "./Task.vue";
import EmptyState from "./EmptyState.vue";
import Pagination from "./Pagination.vue";
import LoadingState from "./LoadingState.vue";
import TaskListDropdown from "@/components/TaskListDropdown.vue";

const loading = ref(true)

const tasks = ref([])

const paginationLinks = ref({next: null, prev: null})
const currentPage = ref(1)
const lastPage = ref(null)
const perPage = ref(null)
const total = ref(null)

const sortBy = ref(null)
const searchQuery = ref(null)

onMounted(() => {
    axios.get(route('tasks.index')).then(response => {
        tasks.value.push(...response.data.data)
        paginationLinks.value.next = response.data.links.next
        paginationLinks.value.prev = response.data.links.prev
        currentPage.value = response.data.meta.current_page
        lastPage.value = response.data.meta.last_page
        perPage.value = response.data.meta.per_page
        total.value = response.data.meta.total

        loading.value = false
    })
})

const nextPage = () => {
    if(paginationLinks.value.next) {
        loading.value = true
        axios.get(paginationLinks.value.next).then(response => {
            tasks.value = []
            tasks.value.push(...response.data.data)
            paginationLinks.value.next = response.data.links.next
            paginationLinks.value.prev = response.data.links.prev
            currentPage.value = response.data.meta.current_page
            lastPage.value = response.data.meta.last_page
            perPage.value = response.data.meta.per_page
            total.value = response.data.meta.total

            loading.value = false
        })
    }
}

const prevPage = () => {
    if(paginationLinks.value.prev) {
        loading.value = true
        axios.get(paginationLinks.value.prev).then(response => {
            tasks.value = []
            tasks.value.push(...response.data.data)
            paginationLinks.value.next = response.data.links.next
            paginationLinks.value.prev = response.data.links.prev
            currentPage.value = response.data.meta.current_page
            lastPage.value = response.data.meta.last_page
            perPage.value = response.data.meta.per_page
            total.value = response.data.meta.total

            loading.value = false
        })
    }
}


</script>

<template>
    <main class="min-h-screen w-full flex items-center justify-center">
        <div class="max-w-2xl w-full mx-auto">
            <div class="mb-4 px-4 sm:px-0 gap-3 flex justify-between items-center ">
                <TaskListDropdown />
                <UserDropdown />
            </div>

            <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
                <div class="px-4 py-5 sm:px-6">

                </div>
                <div class="px-4 py-5 sm:p-6">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li v-for="task in tasks" :key="task.id" class="py-4">
                            <Task :task="task" />
                        </li>
                    </ul>
                    <LoadingState v-if="loading" class="mx-auto my-8" />
                    <EmptyState v-if="!tasks.length && !loading" />
                </div>
                <div class="px-4 py-4 sm:px-6">
                    <Pagination
                        @next="nextPage"
                        @prev="prevPage"
                        :currentItemsCount="tasks.length"
                        :currentPage="currentPage"
                        :perPage="perPage"
                        :total="total"
                        :next="paginationLinks.next"
                        :prev="paginationLinks.prev"
                    />
                </div>
            </div>
        </div>
    </main>
</template>
