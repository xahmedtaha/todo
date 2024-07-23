import {defineStore} from "pinia";
import {ref, computed, watch} from "vue";

export const useTaskStore = defineStore('counter', () => {
    const tasks = ref([])
    const taskLists = ref([])
    function loadTasks() {
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
    }
    function createTask(id) {

    }

    return { count, name, doubleCount, increment }
})
