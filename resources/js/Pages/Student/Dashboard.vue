<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { DataTable } from "simple-datatables"


const spath = '/storage/';
var dataTable = null;


const searchQuery = ref('')
const currentPage = ref(1) // Single current page for all applications
const itemsPerPage = ref(2) // Number of items per page
const applications = ref([
    // Sample data structure for applications
    { id: 1, title: 'Заявка 1', methodist: 'Иванов', submissionDate: '2024-11-01', department: 'Отдел 1', documentLink: '#', status: 'На проверке у методиста' },
    { id: 2, title: 'Заявка 2', methodist: 'Петров', submissionDate: '2024-11-02', department: 'Отдел 2', documentLink: '#', status: 'Отправлен в отдел' },
    { id: 3, title: 'Заявка 3', methodist: 'Сидоров', submissionDate: '2024-11-03', department: 'Отдел 3', documentLink: '#', status: 'Готов к выдаче' },
    { id: 4, title: 'Заявка 4', methodist: 'Александров', submissionDate: '2024-11-04', department: 'Отдел 4', documentLink: '#', status: 'Отказ' },
])



const filteredApplications = computed(() => {
    // Filter applications based on search query
    return applications.value.filter(app =>
        app.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
})
const paginatedApplications = computed(() => {
    // Calculate the start and end index for pagination
    const start = (currentPage.value - 1) * itemsPerPage.value;
    return filteredApplications.value.slice(start, start + itemsPerPage.value);
})
const totalPages = computed(() => {
    // Calculate total pages based on filtered applications
    return Math.ceil(filteredApplications.value.length / itemsPerPage.value);
})

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}
const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Мои заявки
            </h2>
        </template>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Список Заявок</h1>

            <input type="text" v-model="searchQuery" placeholder="Поиск..."
                class="mb-4 p-2 border border-gray-300 rounded" />

            <div class="bg-white rounded-lg shadow-md">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2">Номер</th>
                            <th class="border border-gray-300 p-2">Название</th>
                            <th class="border border-gray-300 p-2">Методист</th>
                            <th class="border border-gray-300 p-2">Дата отправки</th>
                            <th class="border border-gray-300 p-2">Отдел</th>
                            <th class="border border-gray-300 p-2">Документ</th>
                            <th class="border border-gray-300 p-2">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="application in paginatedApplications" :key="application.id">
                            <td class="border border-gray-300 p-2">{{ application.id }}</td>
                            <td class="border border-gray-300 p-2">{{ application.title }}</td>
                            <td class="border border-gray-300 p-2">{{ application.methodist }}</td>
                            <td class="border border-gray-300 p-2">{{ application.submissionDate }}</td>
                            <td class="border border-gray-300 p-2">{{ application.department }}</td>
                            <td class="border border-gray-300 p-2">
                                <a :href="application.documentLink" class="text-blue-500 hover:underline">Ссылка</a>
                            </td>
                            <td class="border border-gray-300 p-2">{{ application.status }}</td>
                        </tr>
                        <tr v-if="!paginatedApplications.length">
                            <td colspan="7" class="border border-gray-300 p-2 text-center">Нет заявок</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="mt-4 flex justify-between">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" @click="previousPage"
                    :disabled="currentPage <= 1">
                    Назад
                </button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" @click="nextPage"
                    :disabled="currentPage >= totalPages">
                    Вперед
                </button>
            </div>
        </div>

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-4">
            <div class="space-y-6">
                <h1 class="text-2xl font-bold mb-4">Список Заявок</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">На проверке у методиста</h2>
                        <div v-if="$page.props.first_check.length > 0" v-for="app in $page.props.first_check"
                            :key="app.p_id" class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">#{{ app.p_id }} {{ app.origin_name }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock h-6 w-6 text-yellow-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <p>Методист: {{ app.m_name }}</p>
                            <p>Дата отправки: {{ app.publish_date }}</p>
                            <p>Отдел: образования</p>
                            <a :href="`/storage/${app.t_path}`" class="text-blue-500 hover:underline">Чистый
                                документ</a><br>
                            <a :href="`/storage/${app.d_path}`" class="text-blue-500 hover:underline">Отправленный
                                документ</a>
                        </div>
                        <div v-else class="text-gray-500">Нет заявок на проверке.</div>
                    </div>

                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">Отправлен в отдел</h2>
                        <div v-if="$page.props.department.length > 0" v-for="app in $page.props.department"
                            :key="app.p_id" class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">#{{ app.p_id }} {{ app.origin_name }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-file-text h-8 w-8 text-blue-500">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                    <path d="M10 9H8"></path>
                                    <path d="M16 13H8"></path>
                                    <path d="M16 17H8"></path>
                                </svg>
                            </div>
                            <p>Методист: {{ app.m_name }}</p>
                            <p>Дата отправки: {{ app.publish_date }}</p>
                            <p>Отдел: образования</p>
                            <a :href="`/storage/${app.t_path}`" class="text-blue-500 hover:underline">Чистый
                                документ</a><br>
                            <a :href="`/storage/${app.d_path}`" class="text-blue-500 hover:underline">Отправленный
                                документ</a>
                        </div>
                        <div v-else class="text-gray-500">Нет заявок, переданных в отдел.</div>
                    </div>

                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">Готов к выдаче</h2>
                        <div v-if="$page.props.ready.length > 0" v-for="app in $page.props.ready" :key="app.p_id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">#{{ app.p_id }} {{ app.origin_name }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-check-circle h-8 w-8 text-green-500">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <path d="m9 11 3 3L22 4"></path>
                                </svg>
                            </div>
                            <p>Методист: {{ app.m_name }}</p>
                            <p>Дата отправки: {{ app.publish_date }}</p>
                            <p>Дата ответа: {{ app.ready_date }}</p>
                            <p>Отдел: образования</p>
                            <a :href="`/storage/${app.rd_path}`" class="text-blue-500 hover:underline">Отправленный
                                документ</a>
                            <p class="text-red-600" v-if="app.rd_ekey == 1">
                                *Электронная подпись
                            </p>
                            <p v-if="app.note">
                                Примечание: {{ app.note }}
                            </p>
                        </div>
                    </div>

                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">Отказ</h2>
                        <div v-if="$page.props.reject.length > 0" v-for="app in $page.props.reject" :key="app.p_id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">#{{ app.p_id }} {{ app.origin_name }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-xcircle h-8 w-8 text-red-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="m15 9-6 6"></path>
                                    <path d="m9 9 6 6"></path>
                                </svg>
                            </div>
                            <p>Методист: {{ app.m_name }}</p>
                            <p>Дата отправки: {{ app.publish_date }}</p>
                            <p>Дата ответа: {{ app.ready_date }}</p>
                            <p>Отдел: образования</p>
                            <a :href="`/storage/${app.d_path}`" class="text-blue-500 hover:underline">Отправленный
                                документ</a>
                            <p>
                                Причина: {{ app.reason }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.datatable-active {
    background-color: rgb(28 100 242);
}

.datatable-active>button {
    color: #fff !important;
}

.application-category {
    transition: all 0.3s ease;
}

.application-category:hover {
    transform: scale(1.05);
}
</style>