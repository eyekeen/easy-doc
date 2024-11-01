<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { DataTable } from "simple-datatables"
import Modal from '@/Components/Modal.vue';


const rejectId = ref();
const reason = ref('');

const modalVisible = ref(false)

const showModal = (pid) => {
    rejectId.value = pid
    modalVisible.value = true
}

const handleClose = () => {
    modalVisible.value = false
}

const updateStatus = async (pid) => {
    try {
        const response = await axios.put(`/methodologist/${pid}`, {
            new_status: 2,
        })

        console.log(response.data)
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }
}

const rejectStatus = async () => {
    
    try {
        const response = await axios.put(`/methodologist/reject/${rejectId.value}`, {
            new_status: 5,
            reason: reason.value
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
                                        <div v-if="petition.status_code == 1" class="inline-flex rounded-md shadow-sm"
                                            role="group">
                                            <span>
                                                <form @submit.prevent="updateStatus(petition.p_id)">
                                                    <input type="hidden" name="new_status" value="2">
                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                                        Отправить в отдел
                                                    </button>
                                                </form>
                                            </span>
                                            <button @click="showModal(petition.p_id)"
                                                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                type="button">
                                                Отказ
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <Modal v-if="modalVisible" :show="modalVisible" :max-width="'md'" :closeable="true" @close="handleClose">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Отказ
                        </h3>
                        <button type="button"
                            class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            @click="handleClose">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form class="space-y-4" @submit.prevent="rejectStatus">
                            <div>
                                <input type="hidden" name="rpid" :value="rejectId" />
                                <label for="message"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Отказ    
                                </label>
                                {{ reason }}
                                <textarea id="message" rows="4"
                                    v-model="reason"
                                    name="reason"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Причина отказа"></textarea>

                            </div>
                            <button type="submit"
                                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Отправить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
