<script setup>
import {onMounted, ref, watch} from "vue";
import UserDropdown from "./UserDropdown.vue";
import Task from "./Task/Task.vue";
import EmptyState from "./States/EmptyState.vue";
import Pagination from "./Pagination.vue";
import LoadingState from "./States/LoadingState.vue";
import TaskListDropdown from "./TaskLists/TaskListDropdown.vue";
import {refDebounced} from "@vueuse/core";
import { PlusIcon } from '@heroicons/vue/20/solid'
import TaskModal from "./Task/TaskModal.vue";

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
const status = ref('pending')
const searchQuery = ref(null)
const debouncedSearchQuery = refDebounced(searchQuery, 500)

onMounted(() => {
    loadLists().then(() => {
        listsLoading.value = false
        loadTasks().then(() => {
            tasksLoading.value = false
            watch(sortBy, () => loadTasks())
            watch(status, () => loadTasks())
            watch(debouncedSearchQuery, () => loadTasks())
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
            searchQuery: searchQuery.value,
            status: status.value,
        }
    }).then(response => {
        tasks.value = response.data.data
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

const saveTask = (data) => {
    tasksLoading.value = true
    if (data.id) {
        axios.put(route('tasks.update', data.id), {
            title: data.title,
            description: data.description,
            due_date: data.due_date,
            status: data.status,
            task_list_id: currentListId.value,
        }).then(response => {
            loadTasks().then(() => tasksLoading.value = false)
        })
    } else {
        axios.post(route('tasks.store'), {
            title: data.title,
            description: data.description,
            due_date: data.due_date,
            status: data.status,
            task_list_id: currentListId.value,
        }).then(response => {
            loadTasks().then(() => tasksLoading.value = false)
        })
    }
}

const deleteTask = (id) => {
    tasksLoading.value = true
    axios.delete(route('tasks.destroy', id)).then(response => {
        loadTasks().then(() => tasksLoading.value = false)
    })
}

const restoreTask = (id) => {
    tasksLoading.value = true
    axios.post(route('tasks.restore', id)).then(response => {
        loadTasks().then(() => tasksLoading.value = false)
    })
}

const taskModalOpen = ref(false)
const modalTaskData = ref({
    id: null,
    title: '',
    description: '',
    status: 'pending',
    due_date: null,
})

const openTaskCreateModal = () => {
    modalTaskData.value = {
        id: null,
        title: '',
        description: '',
        status: 'pending',
        due_date: null,
    }
    taskModalOpen.value = true
}
const openTaskEditModal = (id) => {
    let data = tasks.value.find((task) => task.id === id)
    if(data !== -1) {
        modalTaskData.value = {
            id: data.id,
            title: data.title,
            description: data.description,
            status: data.status,
            due_date: data.due_date,
        }
        taskModalOpen.value = true
    }
}

</script>

<template>
    <main class="min-h-screen w-full flex items-center justify-center">
        <TaskModal :open="taskModalOpen" :task="modalTaskData" @close="taskModalOpen = false" @save="task => saveTask(task)" />
        <div class="max-w-2xl w-full mx-auto">
            <div class="mb-4 px-4 sm:px-0 gap-3 flex justify-between items-center ">
                <TaskListDropdown :loading="listsLoading" :lists="taskLists" @deleteList="deleteList" @saveList="saveList" @changeList="(id) => currentListId = id" :currentList="currentListId" />
                <UserDropdown />
            </div>

            <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
                <div class="px-4 py-5 sm:px-6 flex flex-col md:flex-row gap-4 md:items-center">
                    <div>
                        <input v-model="searchQuery" type="search" name="search" id="search" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search Tasks..." />
                    </div>
                    <div>
                        <select v-model="sortBy" id="sort_by" name="sort_by" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option :value="null">Sort By</option>
                            <option value="title">Title</option>
                            <option value="due_date">Due Date</option>
                        </select>
                    </div>
                    <div>
                        <select v-model="status" id="sort_by" name="sort_by" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>
                    <div class="ml-auto justify-end">
                        <button @click="openTaskCreateModal" type="button" class="rounded-full bg-indigo-600 p-2 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <PlusIcon class="h-5 w-5" aria-hidden="true" />
                        </button>
                    </div>
                </div>
                <div class="px-4 sm:px-6">
                    <ul role="list" class="divide-y divide-gray-200" v-show="!tasksLoading">
                        <li v-for="task in tasks" :key="task.id" class="py-4">
                            <Task @edit="openTaskEditModal" @delete="deleteTask" @restore="restoreTask" :task="task" />
                        </li>
                    </ul>
                    <LoadingState v-if="tasksLoading" class="mx-auto my-8" />
                    <EmptyState v-if="!tasks.length && !tasksLoading" class="my-8 mx-auto" />
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
