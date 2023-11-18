<script setup>
import AdminAlert from '@/Components/AdminLayout/AdminAlert.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CategoryLayout from './CategoryLayout.vue';

const props = defineProps({
  viewCategory: Object,
});

const moduleUrl = 'admin/commerce/categories';
const editUrl = `${moduleUrl}/edit`;
const viewCategoryLabels = [
  { key: 'category_code', title: 'Category Code' },
  { key: 'subcategories', title: 'Subcategories' },
];

const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: !!props.viewCategory.deleted_at && showFlashError.value,
    type: 'default',
    status: 'error',
    message: 'You are viewing a deleted record.',
    description: 'This record cannot be edited or restored.',
  };
});

const onAdminAlertButtonClicked = () => {
  showFlashError.value = false;
};
</script>

<template>
  <CategoryLayout>
    <Head title="View Company" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <div class="h-full divide-y divide-gray-200">
      <div class="mt-6 flex flex-col sm:flex-row pb-6 px-4 sm:px-6 gap-4 sm:gap-0">
        <div class="flex-grow flex flex-col items-start gap-1">
          <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">
            {{ viewCategory.category_name }}
          </h3>
        </div>
        <div v-if="!viewCategory.deleted_at">
          <Link
            :href="route(editUrl, { id: viewCategory.id })"
            as="button"
            class="btn btn-sm btn-block btn-primary sm:min-w-[7rem]"
          >
            Edit
          </Link>
        </div>
      </div>
      <div class="px-4 py-5 sm:px-0 sm:py-0">
        <dl class="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
          <template v-for="label in viewCategoryLabels" :key="label.key">
            <div class="sm:flex sm:px-6 sm:py-5">
              <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                {{ label.title }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                <template v-if="label.key === 'subcategories'">
                  <ul v-if="viewCategory.subcategories?.length > 0">
                    <li v-for="(subcategory, index) in viewCategory.subcategories" :class="index !== 0 && 'mt-3'">
                      {{ subcategory.subcategory_name }}&nbsp;({{ subcategory.subcategory_code }})
                    </li>
                  </ul>
                  <template v-else> - </template>
                </template>
                <template v-else>
                  {{ viewCategory[label.key] ?? '-' }}
                </template>
              </dd>
            </div>
          </template>
        </dl>
      </div>
    </div>
  </CategoryLayout>
</template>
