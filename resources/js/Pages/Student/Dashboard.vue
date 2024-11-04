<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { DataTable } from "simple-datatables"


const spath = '/storage/';
var dataTable = null;


onMounted(() => {
    if (document.getElementById("example")) {
        dataTable = new DataTable("#example", {
            paging: true,
            perPage: 5,
            perPageSelect: [5, 10, 15, 20, 25],
            sortable: true
        });
    } else {
        console.log(document.getElementById("example"));
    }
})

var applications = ref([
    {
        id: 1,
        title: 'Заявка на проверку 1',
        recipient: 'Иван Иванов',
        submissionDate: '2024-11-01',
        department: 'Отдел 1',
        status: 'На проверке',
        documentLink: 'link_to_document_1'
    },
    {
        id: 2,
        title: 'Заявка на передачу 1',
        recipient: 'Петр Петров',
        submissionDate: '2024-11-02',
        department: 'Отдел 2',
        status: 'Передан в отдел',
        documentLink: 'link_to_document_2'
    },
    {
        id: 3,
        title: 'Заявка на выдачу 1',
        recipient: 'Алексей Смирнов',
        submissionDate: '2024-11-03',
        department: 'Отдел 3',
        status: 'Готов к выдаче',
        documentLink: 'link_to_document_3'
    },
    {
        id: 4,
        title: 'Заявка на отказ 1',
        recipient: 'Ольга Кузнецова',
        submissionDate: '2024-11-04',
        department: 'Отдел 4',
        status: 'Отказ',
        documentLink: 'link_to_document_4'
    },
    {
        id: 4,
        title: 'Заявка на отказ 1',
        recipient: 'Ольга Кузнецова',
        submissionDate: '2024-11-04',
        department: 'Отдел 4',
        status: 'Отказ',
        documentLink: 'link_to_document_4'
    },
])

const filteredApplications = (status) => {
    console.log(applications);
    return applications.value.filter(app => app.status === status);
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

        <div class="py-3">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Список всех заявок которые вы отправили или на которые получилил ответ
                    </div>
                </div>
            </div>
        </div>



        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow p-6" v-for="status in $page.props.statuses">
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="#" class="text-sm font-medium text-gray-600">{{ status.status }}</a>
                                <p>1</p>
                            </div>
                            <svg v-if="status.id == 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-clock h-8 w-8 text-yellow-500">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <svg v-if="status.id == 2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-file-text h-8 w-8 text-blue-500">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                <path d="M10 9H8"></path>
                                <path d="M16 13H8"></path>
                                <path d="M16 17H8"></path>
                            </svg>
                            <svg v-if="status.id == 3" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-circle h-8 w-8 text-green-500">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                            <svg v-if="status.id == 5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-xcircle h-8 w-8 text-red-500">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m15 9-6 6"></path>
                                <path d="m9 9 6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-3">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <hr class="h-px my-4 bg-gray-200 border-3 dark:bg-gray-700">
            </div>
        </div>


        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="space-y-6">
                <h1 class="text-2xl font-bold mb-4">Список Заявок</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">На проверке у методиста</h2>
                        <div v-for="app in filteredApplications('На проверке')" :key="app.id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">{{ app.title }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock h-6 w-6 text-yellow-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <p>Получатель: {{ app.recipient }}</p>
                            <p>Дата отправки: {{ app.submissionDate }}</p>
                            <p>Отдел: {{ app.department }}</p>
                            <p>Статус: {{ app.status }}</p>
                            <a :href="app.documentLink" class="text-blue-500 hover:underline">Документ</a>
                        </div>
                    </div>

                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">Передан в отдел</h2>
                        <div v-for="app in filteredApplications('Передан в отдел')" :key="app.id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">{{ app.title }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock h-6 w-6 text-yellow-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <p>Получатель: {{ app.recipient }}</p>
                            <p>Дата отправки: {{ app.submissionDate }}</p>
                            <p>Отдел: {{ app.department }}</p>
                            <p>Статус: {{ app.status }}</p>
                            <a :href="app.documentLink" class="text-blue-500 hover:underline">Документ</a>
                        </div>
                    </div>

                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">Готов к выдаче</h2>
                        <div v-for="app in filteredApplications('Готов к выдаче')" :key="app.id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">{{ app.title }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock h-6 w-6 text-yellow-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <p>Получатель: {{ app.recipient }}</p>
                            <p>Дата отправки: {{ app.submissionDate }}</p>
                            <p>Отдел: {{ app.department }}</p>
                            <p>Статус: {{ app.status }}</p>
                            <a :href="app.documentLink" class="text-blue-500 hover:underline">Документ</a>
                        </div>
                    </div>

                    <div class="application-category">
                        <h2 class="text-xl font-semibold mb-2">Отказ</h2>
                        <div v-for="app in filteredApplications('Отказ')" :key="app.id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <div class="flex items-center">
                                <h3 class="font-medium flex-1">{{ app.title }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock h-6 w-6 text-yellow-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <p>Получатель: {{ app.recipient }}</p>
                            <p>Дата отправки: {{ app.submissionDate }}</p>
                            <p>Отдел: {{ app.department }}</p>
                            <p>Статус: {{ app.status }}</p>
                            <a :href="app.documentLink" class="text-blue-500 hover:underline">Документ</a>
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