<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, defineProps, computed } from 'vue';

const props = defineProps({
  required: Array
})



const selectedDocument = ref(null)
const formData = ref({})



const submitForm = async () => {

  const petition = document.getElementById('petition').value
  const meth = document.getElementById('meth').value


  try {
    const response = await axios.post('/student/send', {
      petition_id: petition,
      meth: meth,
      requiredData: formData.value
    });

    if (response.data == 'success') {
      alertSuccess.value = true;
      alertError.value = false;
    } else {
      alertSuccess.value = false;
      alertError.value = true;
    }

    console.log(response.data);

  } catch (error) {
    alertSuccess.value = false;
    alertError.value = true;
    console.error(error);
  }

  // Handle form submission
  console.log('Form submitted:', formData.value
  );
  // Reset form data
  formData.value = {};
  // Optionally close the form or reset selected document
  selectedDocument.value = null; // Close form after submission
}


const alertSuccess = ref(false);
const alertError = ref(false);


</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Форма отправки заявки или запроса справки
      </h2>
    </template>



    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-4">
      <div class="space-y-6">
        <div class="container mx-auto p-4">
          <h1 class="text-2xl font-bold mb-4">Список Документов</h1>


          <div id="alert-success"
          v-if="alertSuccess"
            class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
            role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
              viewBox="0 0 20 20">
              <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
              Заявка оптравленна.
            </div>
            <button type="button"
              class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700"
              data-dismiss-target="#alert-success" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
            </button>
          </div>
          <div id="alert-error"
          v-if="alertError"
            class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
              viewBox="0 0 20 20">
              <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
              Произошла ошибка. Обратитесь к администратору.
            </div>
            <button type="button"
              class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
              data-dismiss-target="#alert-error" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
            </button>
          </div>



          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="doc in $page.props.templates" :key="doc.id"
              class="bg-white p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow"
              @click="selectedDocument = selectedDocument === doc ? null : doc">
              <h3 class="font-medium">{{ doc.origin_name }}</h3>
              <p>Lorem.</p>
            </div>
          </div>

          <div v-if="selectedDocument" class="mt-6 bg-gray-50 p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">{{ selectedDocument.origin_name }} - Форма</h2>
            <form @submit.prevent="submitForm">
              <input type="hidden" id="meth" name="meth" :value="$page.props.method[0].id">
              <input type="hidden" id="petition" name="petition_id" :value="selectedDocument.id">
              <div v-for="field in $page.props.required" :key="field.id" class="mb-4">
                <span v-if="field.template_id == selectedDocument.id">
                  <label :for="field.key" class="block text-sm font-medium text-gray-700">{{ field.value }}</label>
                  <input v-model="formData[field.key]" type="text" :id="field.key"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500"
                    required />
                </span>
              </div>
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                Отправить
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
