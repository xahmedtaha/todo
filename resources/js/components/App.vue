<script setup>
import {onMounted, ref, watch} from "vue";
import UserDropdown from "./UserDropdown.vue";
import Task from "./Task.vue";
import EmptyState from "./EmptyState.vue";
import Pagination from "./Pagination.vue";
import LoadingState from "./LoadingState.vue";
import TaskListDropdown from "./TaskListDropdown.vue";

const tasksLoading = ref(true)
const listsLoading = ref(true)

const tasks = ref([])
const taskLists = ref([])

const currentListId = ref(null)

const paginationLinks = ref({next: null, prev: null})
const currentPage = ref(1)
const lastPage = ref(null)
const perPage = ref(null)
const total = ref(null)

const sortBy = ref(null)
const searchQuery = ref(null)

onMounted(() => {
    loadLists().then(() => {
        listsLoading.value = false
        loadTasks().then(() => {
            tasksLoading.value = false
            watch(sortBy, () => loadTasks())
            watch(searchQuery, () => loadTasks())
            watch(currentListId, () => loadTasks())
        })
    })
})

const loadLists = () => {
    return axios.get(route('task-lists.index')).then(response => {
        taskLists.value.push(...response.data.data)
        currentListId.value = taskLists.value[0]?.id
    })
}
const loadTasks = () => {
    return axios.get(route('tasks.index'), {
        params: {
            taskListId: currentListId.value,
            sortBy: sortBy.value,
            searchQuery: sortBy.value
        }
    }).then(response => {
        tasks.value.push(...response.data.data)
        paginationLinks.value.next = response.data.links.next
        paginationLinks.value.prev = response.data.links.prev
        currentPage.value = response.data.meta.current_page
        lastPage.value = response.data.meta.last_page
        perPage.value = response.data.meta.per_page
        total.value = response.data.meta.total
    })
}

const nextPage = () => {
    if(paginationLinks.value.next) {
        tasksLoading.value = true
        axios.get(paginationLinks.value.next).then(response => {
            tasks.value = []
            tasks.value.push(...response.data.data)
            paginationLinks.value.next = response.data.links.next
            paginationLinks.value.prev = response.data.links.prev
            currentPage.value = response.data.meta.current_page
            lastPage.value = response.data.meta.last_page
            perPage.value = response.data.meta.per_page
            total.value = response.data.meta.total

            tasksLoading.value = false
        })
    }
}

const prevPage = () => {
    if(paginationLinks.value.prev) {
        tasksLoading.value = true
        axios.get(paginationLinks.value.prev).then(response => {
            tasks.value = []
            tasks.value.push(...response.data.data)
            paginationLinks.value.next = response.data.links.next
            paginationLinks.value.prev = response.data.links.prev
            currentPage.value = response.data.meta.current_page
            lastPage.value = response.data.meta.last_page
            perPage.value = response.data.meta.per_page
            total.value = response.data.meta.total

            tasksLoading.value = false
        })
    }
}


const saveList = (data) => {
    listsLoading.value = true
    if (data.id) {
        axios.put(route('task-lists.update', data.id), {
            title: data.title,
        }).then(response => {
            taskLists.value[taskLists.value.findIndex((item) => item.id === response.data.data.id)] = response.data.data
            listsLoading.value = false
        })
    } else {
        axios.post(route('task-lists.store'), {
            title: data.title,
        }).then(response => {
            taskLists.value.push(response.data.data)
            currentListId.value = response.data.data.id
            listsLoading.value = false
        })
    }
}

const deleteList = () => {
    if(taskLists.value.length > 1) {
        listsLoading.value = true
        axios.delete(route('task-lists.destroy', currentListId.value)).then(response => {
            taskLists.value.splice(taskLists.value.findIndex((item) => item.id === currentListId.value), 1)
            currentListId.value = taskLists.value[0]?.id
            listsLoading.value = false
        })
    }
}

</script>

<template>
    <main class="min-h-screen w-full flex items-center justify-center">
        <div class="max-w-2xl w-full mx-auto">
            <div class="mb-4 px-4 sm:px-0 gap-3 flex justify-between items-center ">
                <TaskListDropdown :loading="listsLoading" :lists="taskLists" @deleteList="deleteList" @saveList="saveList" @changeList="(id) => currentListId = id" :currentList="currentListId" />
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
                    <LoadingState v-if="tasksLoading" class="mx-auto my-8" />
                    <EmptyState v-if="!tasks.length && !tasksLoading" />
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
