<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const isModalOpen = ref(false)
const refusalReason = ref('')
const selectedApplicationId = ref(null)

const isUploadModalOpen = ref(false)
const uploadNotes = ref('')
const selectedUploadApplicationId = ref(null)
const uploadedFile = ref(null)

const electronicSignature = async (applicationId) => {

    try {
        const response = await axios.post('/department/electronickey', {
            new_status: 3,
            pid: applicationId
        })

        console.log(response.data)
        location.reload();
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }

    // Implement logic for handling the refusal
    // location.reload();
    // Logic to handle electronic signature
    console.log(`Applying electronic signature for application ${applicationId}...`);
}

const openModal = (applicationId) => {
    selectedApplicationId.value = applicationId;
    isModalOpen.value = true;
}
const closeModal = () => {
    isModalOpen.value = false;
    refusalReason.value = ''; // Clear the reason when closing
}

const submitRefusal = async () => {
    if (refusalReason.value.trim() === '') {
        alert('Причина отказа не может быть пустой!');
        return;
    }

    try {
        const response = await axios.put(`/department/reject/${selectedApplicationId.value}`, {
            new_status: 5,
            reason: refusalReason.value
        })

        console.log(response.data)
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }

    // Implement logic for handling the refusal
    location.reload();
}

const openUploadModal = (applicationId) => {
    selectedUploadApplicationId.value = applicationId;
    isUploadModalOpen.value = true;
}
const handleFileUpload = (event) => {
    uploadedFile.value = event.target.files[0]; // Capture the uploaded file
}
const submitUpload = async () => {
    if (!uploadedFile.value) {
        alert('Пожалуйста, выберите файл для загрузки.');
        return;
    }

    const formData = new FormData();
    formData.append("signedDoc", uploadedFile.value);
    formData.append("new_status", 3);
    formData.append("note", uploadNotes.value);

    try {
        const response = await axios.post(`/department/${selectedUploadApplicationId.value}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })

        console.log(response.data)
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
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

    location.reload();
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="application in $page.props.petitions" :key="application.p_id"
                        class="bg-white p-4 rounded-lg shadow-md mb-4">
                        <h3 class="font-medium">#{{ application.p_id }} {{ application.origin_name }}</h3>
                        <p>ФИО: {{ application.s_name }}</p>
                        <p>Ссылка на документ: <a :href="`/storage/${application.d_path}`"
                                class="text-blue-500 hover:underline">Документ</a></p>
                        <p>Дата отправки: {{ application.publish_date }}</p>
                        <p>Статус: {{ application.status }}</p>

                        <div class="mt-4">
                            <button
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                @click="electronicSignature(application.p_id)">
                                Электронная подпись
                            </button>
                            <button
                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                @click="openUploadModal(application.p_id)">
                                Загрузить скан
                            </button>
                            <button
                                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                @click="openModal(application.p_id)">
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
                                <button
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
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
                            <h2 class="text-xl font-semibold mb-4">Загрузка скана для Заявки #{{
                                selectedUploadApplicationId
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
        </div>


    </AuthenticatedLayout>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s;
}

.modal-enter,
.modal-leave-to

/* .modal-leave-active in <2.1.8 */
    {
    opacity: 0;
}
</style>