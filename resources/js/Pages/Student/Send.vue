<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, defineProps, computed } from 'vue';

const petition = ref();
const meth = ref();

const props = defineProps({
  required: Array
})

const formData = ref({})

var selectedFields = null

watch(petition, () => {
  console.log(`petition ` + petition.value);
  selectedFields = computed(() => {
    return props.required.filter(field => field.template_id == petition.value)
  })

  console.log(props.required);
  console.log(selectedFields);
  
});

watch(meth, () => {
  console.log(`meth ` + meth.value);
})

const sendPetition = async function () {
  try {
    const response = await axios.post('/stundet/send', {
      petition_id: petition.value,
      meth: meth.value,
      requiredData: formData.value
    });

    console.log(response.data);
  } catch (error) {
    console.error('Error:', error.message);
  }
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

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            Форма отправки заявки или запроса справки
          </div>
        </div>
      </div>
    </div>


    <form class="max-w-sm mx-auto" @submit.prevent="sendPetition">
      <label for="default" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Справка/заявка</label>
      <select id="default" v-model="petition" required
        class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option v-for="template in $page.props.templates" :value="template.id">
          {{ template.origin_name }}
        </option>
      </select>

      <label for="default" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        Методист
      </label>
      <select id="default" v-model="meth"
        class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option v-for="meth in $page.props.method" :value="meth.id">
          {{ meth.name }}
        </option>
      </select>
      <div v-if="selectedFields" v-for="(field, index) in selectedFields" :key="index">
        <label :for="field.key" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ field.value }}
        </label>
        <input type="text" :id="field.key" :name="field.key" v-model="formData[field.key]"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
      </div>
      <button type="submit"
        class="text-white mt-2 mb-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Отправить</button>
    </form>


  </AuthenticatedLayout>
</template>
