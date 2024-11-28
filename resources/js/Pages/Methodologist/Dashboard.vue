<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';


const isModalOpen = ref(false)
const refusalReason = ref('')
const selectedApplicationId = ref(null)

const sendToDepartment = async (applicationId) => {
    try {
        const response = await axios.put(`/methodologist/${applicationId}`, {
            new_status: 2,
        })

        console.log(response.data)
        location.reload()
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }
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

    // Implement logic for handling the refusal
    console.log(`Application ${selectedApplicationId.value} rejected for reason: ${refusalReason.value}`);

    try {
        const response = await axios.put(`/methodologist/reject/${selectedApplicationId.value}`, {
            new_status: 5,
            reason: refusalReason.value
        })

        console.log(response.data)
        location.reload();
    } catch (error) {
        console.error(error.response ? error.response.data : error.message)
    }

    // Close the modal and reset
    closeModal();
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

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="application in $page.props.petitions" :key="application.p_id"
                            class="bg-white p-4 rounded-lg shadow-md mb-4">
                            <h3 class="font-medium">Заявка #{{ application.p_id }}</h3>
                            <p>ФИО: {{ application.s_name }}</p>
                            <p>Название заявки: {{ application.origin_name }}</p>
                            <p>Ссылка на документ: <a :href="`/storage/${application.d_path}`"
                                    class="text-blue-500 hover:underline">Документ</a></p>
                            <p>Дата отправки: {{ application.publish_date }}</p>
                            <p>Статус: {{ application.status }}</p>

                            <p v-if="application.status_code == 5">Причина отказа: {{ application.reason }}</p>

                            <div class="mt-4" v-if="application.status_code == 1">
                                <button
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                    @click="sendToDepartment(application.p_id)">
                                    Отправить в отдел
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
                                <textarea v-model="refusalReason"
                                    class="w-full h-24 p-2 border border-gray-300 rounded-md"
                                    placeholder="Введите причину..."></textarea>
                                <div class="mt-4">
                                    <button
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                        @click="submitRefusal">
                                        Подтвердить
                                    </button>
                                    <button
                                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 ml-2"
                                        @click="closeModal">
                                        Отмена
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s;
}
.modal-enter, .modal-leave-to /* .modal-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>