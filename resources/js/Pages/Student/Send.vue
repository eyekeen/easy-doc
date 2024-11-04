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

    console.log(response.data);
  } catch (error) {
    console.error('Error:', error.message);
  }

  // Handle form submission
  console.log('Form submitted:', formData);
  // Reset form data
  formData = {};
  // Optionally close the form or reset selected document
  selectedDocument = null; // Close form after submission
}







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
