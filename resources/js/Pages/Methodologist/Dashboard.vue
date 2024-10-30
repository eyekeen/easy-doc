<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { DataTable } from "simple-datatables"

const selectedStatus = ref('')

const changeStatus = async ($event) => {
    const petition_id = $event.target.attributes.pid.value;

    try {
        const response = await axios.put(`/methodologist/${petition_id}`, {
            new_status: selectedStatus
        })

        console.log(response.data)
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }
}

var table = null;

onMounted(() => {
    if (document.getElementById("m-table")) {
        table = new DataTable("#m-table", {
            paging: true,
            perPage: 5,
            perPageSelect: [5, 10, 15, 20, 25],
            sortable: true
        });
    } else {
        console.log(document.getElementById("m-table"));
    }
})

const selectedPetitionId = ref(null);
const selectedAction = ref('');

const openModal = (id) => {
    selectedPetitionId.value = id;
    document.getElementById('action-modal').classList.remove('hidden');
};

const closeModal = () => {
    document.getElementById('action-modal').classList.add('hidden');
    selectedPetitionId.value = null;
    selectedAction.value = '';
};

const handleActionSubmit = async () => {
    try {
        await axios.post(`/methodologist/${selectedPetitionId.value}/action`, { action: selectedAction.value });
        closeModal();
        // Здесь можно добавить обновление таблицы или другие действия после успешного отправки
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
    }
};

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Заявки от студентов
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <table id="m-table">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="flex items-center">
                                            #
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Студент
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Заявка
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Документ
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Дата отправки
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Статус
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Действия
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Apple Inc.
                                    </td>
                                    <td>AAPL</td>
                                    <td>$192.58</td>
                                    <td>$3.04T</td>
                                </tr> -->
                                <tr v-for="petition in $page.props.petitions" :key="petition.id">
                                    <td scope="row">
                                        {{ petition.p_id }}
                                    </td>
                                    <td>
                                        {{ petition.s_name }}
                                    </td>
                                    <td>
                                        {{ petition.origin_name }}
                                    </td>
                                    <td>
                                        <a :href="`/storage/${petition.d_path}`">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                            </svg>

                                        </a>
                                    </td>
                                    <td>
                                        {{ petition.publish_date }}
                                    </td>
                                    <td>
                                        {{ petition.status }}
                                    </td>
                                    <td>
                                        <button @click="openModal(petition.p_id)"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Действия
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- Модальное окно -->
                            <div id="action-modal"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                <div class="bg-white p-8 border rounded-lg shadow-md max-w-sm w-full">
                                    <h2 class="text-xl font-semibold mb-4">Выберите действие</h2>

                                    <form @submit.prevent="handleActionSubmit">
                                        <input type="hidden" name="petition_id" :value="selectedPetitionId">

                                        <select v-model="selectedAction" required>
                                            <option value="">Выберите действие</option>
                                            <option value="approve">Одобрить</option>
                                            <option value="reject">Отклонить</option>
                                            <option value="hold">Приостановить</option>
                                            <!-- Добавьте другие варианты действий по необходимости -->
                                        </select>

                                        <button type="submit"
                                            class="mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                            Применить
                                        </button>
                                    </form>
                                </div>
                            </div><!-- Модальное окно -->
                            <div id="action-modal"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                <div class="bg-white p-8 border rounded-lg shadow-md max-w-sm w-full">
                                    <h2 class="text-xl font-semibold mb-4">Выберите действие</h2>

                                    <form @submit.prevent="handleActionSubmit">
                                        <input type="hidden" name="petition_id" :value="selectedPetitionId">

                                        <select v-model="selectedAction" required>
                                            <option value="">Выберите действие</option>
                                            <option value="approve">Одобрить</option>
                                            <option value="reject">Отклонить</option>
                                            <option value="hold">Приостановить</option>
                                            <!-- Добавьте другие варианты действий по необходимости -->
                                        </select>

                                        <button type="submit"
                                            class="mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                            Применить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
