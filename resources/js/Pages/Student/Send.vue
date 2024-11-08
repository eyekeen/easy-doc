<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, defineProps, computed } from 'vue';
import Toast from '@/Components/Toast.vue';


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

    console.log(response.data);

    if (response.status === 200) {
      toastMessage.value = 'Process completed successfully!';
      toastType.value = 'success';
    } else {
      toastMessage.value = 'Unexpected response from the server.';
      toastType.value = 'error';
    }

  } catch (error) {
    toastMessage.value = 'An error occurred during the process.';
    toastType.value = 'error';
    console.error(error);
  } finally {
    showToast.value = true;
  }

  // Handle form submission
  console.log('Form submitted:', formData);
  // Reset form data
  formData = {};
  // Optionally close the form or reset selected document
  selectedDocument = null; // Close form after submission
}


const showToast = ref(true);
const toastMsg = ref('');
const toastType = ref('success');


</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Форма отправки заявки или запроса справки
      </h2>
    </template>

    

    <div id="toast-top-right"
    v-if="showToast"
      class="fixed justify-betwee flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800"
      role="alert">
      <div class="text-sm font-normal">Top right positioning.</div>
      <button type="button"
        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
        data-dismiss-target="#toast-top-right" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
      </button>
    </div>

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-4">
      <div class="space-y-6">
        <div class="container mx-auto p-4">
          <h1 class="text-2xl font-bold mb-4">Список Документов</h1>

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
