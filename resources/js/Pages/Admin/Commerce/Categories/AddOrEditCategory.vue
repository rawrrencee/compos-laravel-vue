<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';
import CategoryLayout from './CategoryLayout.vue';

const props = defineProps({
  errorMessage: String,
  category: Object | undefined,
});
const inputFields = [
  { key: 'category_name', title: 'Category Name' },
  { key: 'category_code', title: 'Category Code', maxlength: 4 },
  { key: 'description', title: 'Description' },
];
const categoryForm = useForm({
  category_name: props.category?.category_name ?? '',
  category_code: props.category?.category_code ?? '',
  description: props.category?.description ?? '',
});
const showFlashError = ref(true);
const flashError = computed(() => {
  return {
    show: categoryForm.hasErrors && showFlashError.value,
    type: 'default',
    status: 'error',
    message: 'Please complete or correct the required fields.',
  };
});

const onAdminAlertButtonClicked = () => {
  showFlashError.value = false;
};
</script>

<template>
  <CategoryLayout>
    <Head :title="`${!!category ? 'Edit' : 'Add New'} Category`" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <div class="sm:px-6 lg:px-8">
      <form class="px-4 pt-4 sm:px-0 flex flex-col h-full" @submit.prevent="submit">
        <div class="flex-1 grow grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pb-12">
          <div class="sm:col-span-4" v-for="input in inputFields" :key="input.key">
            <template v-if="input.key === 'description'">
              <label :for="input.key" class="block text-sm font-medium leading-6 text-gray-900">{{
                input.title
              }}</label>
              <div class="mt-2 flex flex-col gap-1">
                <textarea class="textarea textarea-bordered textarea-md w-full max-w-lg"></textarea>
              </div>
            </template>
            <template v-else>
              <label :for="input.key" class="block text-sm font-medium leading-6 text-gray-900">{{
                input.title
              }}</label>
              <div class="mt-2 flex flex-col gap-1">
                <input
                  :type="input.type ?? 'text'"
                  :id="input.key"
                  :name="input.key"
                  :maxlength="input.maxlength"
                  class="input input-bordered w-full max-w-lg"
                  v-model="categoryForm[input.key]"
                  :class="[
                    categoryForm.errors[input.key] ? 'border-error' : '',
                    input.key.includes('_code') && 'uppercase',
                  ]"
                  @input="() => categoryForm.clearErrors([input.key])"
                />
                <span v-if="categoryForm.errors[input.key]" class="text-error">
                  {{ categoryForm.errors[input.key] }}
                </span>
              </div>
            </template>
          </div>
        </div>

        <StickyFooter back-url="admin/commerce/categories" />
      </form>
    </div>
  </CategoryLayout>
</template>
