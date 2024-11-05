<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { DataTable } from "simple-datatables"
import Modal from '@/Components/Modal.vue';


const rejectId = ref(null);
const reason = ref('');
var selectedFile = ref(null);


const readyId = ref(null);
const note = ref('');

const rejectModalVisible = ref(false)
const readyModalVisible = ref(false)

const showRejectModal = (pid) => {
    rejectId.value = pid
    rejectModalVisible.value = true
}

const handleRejectClose = () => {
    rejectModalVisible.value = false
}

const showReadyModal = (pid) => {
    readyId.value = pid
    readyModalVisible.value = true
}

const handleReadyClose = () => {
    readyModalVisible.value = false
}


const makeReady = async () => {

    if (!selectedFile) {
        alert("Выберите файл");
        return;
    }

    // Create FormData instance to prepare file data
    const formData = new FormData();
    formData.append("signedDoc", selectedFile);
    formData.append("new_status", 3);
    formData.append("note", note.value);

    try {
        const response = await axios.post(`/department/${readyId.value}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })

        console.log(response.data)
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }
}


const testKey = async (pid) => {

    console.log(123);


    try {
        const response = await axios.post(`/department/test`, {
            new_status: 3,
            pid: pid
        })

        console.log(response.data)
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }
}

const rejectStatus = async () => {

    try {
        const response = await axios.put(`/department/reject/${rejectId.value}`, {
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

const applications = ref([
    {
        id: 1,
        fullName: 'Иванов Иван Иванович',
        title: 'Заявка на академический отпуск',
        documentLink: 'link_to_document_1',
        submissionDate: '2024-11-01',
        status: 'На проверке'
    },
    {
        id: 2,
        fullName: 'Петров Петр Петрович',
        title: 'Заявка на перевод',
        documentLink: 'link_to_document_2',
        submissionDate: '2024-11-02',
        status: 'На проверке'
    },
    // Add more applications as needed
])
const isModalOpen = ref(false)
const refusalReason = ref('')
const selectedApplicationId = ref(null)

const isUploadModalOpen = ref(false)
const uploadNotes = ref('')
const selectedUploadApplicationId = ref(null)
const uploadedFile = ref(null)

const electronicSignature = (applicationId) => {
    // Logic to handle electronic signature
    console.log(`Applying electronic signature for application ${applicationId}...`);
}
const uploadScan = (applicationId) => {
    // Logic to handle scan upload
    console.log(`Upload scan for application ${applicationId}...`);
    // You can implement file upload logic here
}
const openModal = (applicationId) => {
    selectedApplicationId.value = applicationId;
    isModalOpen.value = true;
}
const closeModal = () => {
    isModalOpen.value = false;
    refusalReason.value = ''; // Clear the reason when closing
}
const submitRefusal = () => {
    if (refusalReason.value.trim() === '') {
        alert('Причина отказа не может быть пустой!');
        return;
    }

    // Implement logic for handling the refusal
    console.log(`Application ${selectedApplicationId.value} rejected for reason: ${refusalReason.value}`);

    // Close the modal and reset
    closeModal();
}

const openUploadModal = (applicationId) => {
    selectedUploadApplicationId.value = applicationId;
    isUploadModalOpen.value = true;
}
const handleFileUpload = (event) => {
    uploadedFile.value = event.target.files[0]; // Capture the uploaded file
}
const submitUpload = () => {
    if (!uploadedFile.value) {
        alert('Пожалуйста, выберите файл для загрузки.');
        return;
    }

    // Logic to handle file upload with notes
    console.log(`Uploading file for application ${selectedUploadApplicationId.value} with notes: ${uploadNotes.value}`);
    console.log(uploadedFile.value)
    // Close the upload modal and reset the form
    closeUploadModal();
}

const closeUploadModal = () => {
    isUploadModalOpen.value = false;
    uploadedFile.value = null; // Clear the uploaded file when closing
    uploadNotes.value = ''; // Clear notes
}

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Заявки от студентов
            </h2>
        </template>

        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Заявки от студентов</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="application in applications" :key="application.id"
                    class="bg-white p-4 rounded-lg shadow-md mb-4">
                    <h3 class="font-medium">Заявка #{{ application.id }}</h3>
                    <p>ФИО: {{ application.fullName }}</p>
                    <p>Название заявки: {{ application.title }}</p>
                    <p>Ссылка на документ: <a :href="application.documentLink"
                            class="text-blue-500 hover:underline">Документ</a></p>
                    <p>Дата отправки: {{ application.submissionDate }}</p>
                    <p>Статус: {{ application.status }}</p>

                    <div class="mt-4">
                        <button class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 mr-2"
                            @click="electronicSignature(application.id)">
                            Электронная подпись
                        </button>
                        <button class="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 mr-2"
                            @click="openUploadModal(application.id)">
                            Загрузить скан
                        </button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600"
                            @click="openModal(application.id)">
                            Отказ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal for rejection reason -->
            <transition name="modal">
                <div v-if="isModalOpen"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-1/3">
                        <h2 class="text-xl font-semibold mb-4">Причина отказа</h2>
                        <textarea v-model="refusalReason" class="w-full h-24 p-2 border border-gray-300 rounded-md"
                            placeholder="Введите причину..."></textarea>
                        <div class="mt-4">
                            <button class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600"
                                @click="submitRefusal">
                                Подтвердить
                            </button>
                            <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 ml-2"
                                @click="closeModal">
                                Отмена
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
            <!-- Modal for uploading scan -->
            <transition name="modal">
                <div v-if="isUploadModalOpen"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-1/3">
                        <h2 class="text-xl font-semibold mb-4">Загрузка скана для Заявки #{{ selectedUploadApplicationId
                            }}</h2>
                        <input type="file" @change="handleFileUpload"
                            class="block w-full mb-4 border border-gray-300 rounded-md" />
                        <textarea v-model="uploadNotes" class="w-full h-24 p-2 border border-gray-300 rounded-md"
                            placeholder="Введите примечания..."></textarea>
                        <div class="mt-4">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                                @click="submitUpload">
                                Отправить
                            </button>
                            <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 ml-2"
                                @click="closeUploadModal">
                                Отмена
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

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
                                        <div v-if="petition.status_code == 2" class="inline-flex rounded-md shadow-sm"
                                            role="group">
                                            <span class="mr-2">
                                                <form class="mb-1">
                                                    <input type="hidden" name="new_status" value="3">
                                                    <button type="button" @click="testKey(petition.p_id)"
                                                        class="inline-flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                        Подписать(Электронная)
                                                    </button>
                                                </form>
                                                <form>
                                                    <input type="hidden" name="new_status" value="3">
                                                    <button type="button" @click="showReadyModal(petition.p_id)"
                                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        Подписать(скан)
                                                    </button>
                                                </form>
                                            </span>
                                            <button @click="showRejectModal(petition.p_id)"
                                                class="block focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
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
        <Modal v-if="rejectModalVisible" :show="rejectModalVisible" :max-width="'md'" :closeable="true"
            @close="handleRejectClose">
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
                            @click="handleRejectClose">
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
                                <textarea id="message" rows="4" v-model="reason" name="reason"
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
        <Modal v-if="readyModalVisible" :show="readyModalVisible" :max-width="'md'" :closeable="true"
            @close="handleReadyClose">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Скан
                        </h3>
                        <button type="button"
                            class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            @click="handleReadyClose">
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
                        <form class="space-y-4" @submit.prevent="makeReady" enctype="multipart/form-data">
                            <div>
                                <input type="hidden" name="rpid" :value="readyId" />
                                <div class="mb-2">

                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                        for="signed_doc">Скан подписанного документа</label>
                                    <input @change="handleFileUpload"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="signed_doc" name="signed_doc" required type="file">

                                </div>
                                <div>
                                    <label for="note"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Примечание
                                    </label>
                                    <input type="text" id="note" v-model="note"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Если есть..." />
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Отправить скан
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
