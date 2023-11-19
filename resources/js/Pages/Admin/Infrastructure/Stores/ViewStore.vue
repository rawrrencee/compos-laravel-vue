<script setup>
import StoreLayout from '@/Pages/Admin/Infrastructure/Stores/StoreLayout.vue';
import { openInNewWindow } from '@/Util/Common';
import { getImgSrcFromPath } from '@/Util/Photo';
import { ArrowRightOnRectangleIcon, PhotoIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';
import ColouredBadge from '../../../../Components/AdminPages/ColouredBadge.vue';

const props = defineProps({
  viewStore: Object,
});

const moduleUrl = 'admin/infrastructure/stores';
const editUrl = `${moduleUrl}/edit`;
const viewSupplierLabels = [
  { key: 'company_name', title: 'Company Name' },
  { key: 'store_code', title: 'Store Code' },
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
  { key: 'tax_percentage', title: 'Tax Percentage' },
  { key: 'include_tax', title: 'Include Tax' },
];

const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: !!props.viewStore.deleted_at && showFlashError.value,
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
  <StoreLayout>
    <Head title="View Supplier" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <div class="h-full divide-y divide-gray-200">
      <div class="pb-6">
        <div class="h-24 bg-primary sm:h-20 lg:h-28" />
        <div class="lg:-mt-15 -mt-12 flow-root px-4 sm:-mt-8 sm:flex sm:items-end sm:px-6">
          <div>
            <div class="-m-1 flex">
              <div
                class="flex h-24 w-24 items-center justify-center rounded-lg border-4 border-white bg-white shadow-md sm:h-40 sm:w-40 lg:h-48 lg:w-48"
              >
                <img
                  v-if="viewStore?.img_url || viewStore?.img_path"
                  :src="viewStore?.img_path ? getImgSrcFromPath(viewStore?.img_path) : viewStore?.img_url"
                  class="h-auto w-full"
                />
                <PhotoIcon
                  class="h-24 w-24 flex-shrink-0 text-gray-300 sm:h-40 sm:w-40 lg:h-48 lg:w-48"
                  aria-hidden="true"
                  v-else
                />
              </div>
            </div>
          </div>
          <div class="mt-6 sm:ml-6 sm:flex-1">
            <div>
              <div class="flex flex-col items-start gap-1">
                <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">
                  {{ viewStore.store_name }}
                </h3>
                <ColouredBadge :data="viewStore.active" data-type="boolean" />
              </div>
            </div>
            <div v-if="!viewStore.deleted_at" class="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
              <Link
                :href="route(editUrl, { id: viewStore.id })"
                as="button"
                class="btn btn-primary btn-sm btn-block sm:max-w-[7rem]"
              >
                Edit
              </Link>
            </div>
          </div>
        </div>
      </div>
      <div class="px-4 py-5 sm:px-0 sm:py-0">
        <dl class="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
          <template v-for="label in viewSupplierLabels" :key="label.key">
            <div class="sm:flex sm:px-6 sm:py-5">
              <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                {{ label.title }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                <template v-if="['img_url', 'website'].includes(label.key)">
                  <a
                    :href="viewStore[label.key]"
                    v-if="viewStore[label.key]"
                    class="link break-all"
                    @click.prevent="openInNewWindow(viewStore[label.key])"
                    >{{ viewStore[label.key] }}</a
                  >
                  <span v-else>-</span>
                </template>
                <template v-else-if="['include_tax'].includes(label.key)">
                  <ColouredBadge :data="viewStore[label.key]" data-type="yesNo" />
                </template>
                <template v-else-if="['company_name'].includes(label.key)">
                  <Link
                    class="link flex items-center gap-1"
                    :href="route('admin/infrastructure/companies/view', { id: viewStore.company.id })"
                  >
                    <span>{{ viewStore.company?.company_name }}</span>
                    <ArrowRightOnRectangleIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                  </Link>
                </template>
                <template v-else-if="['tax_percentage'].includes(label.key)">
                  {{ viewStore[label.key] ?? '-' }}%
                </template>
                <template v-else>
                  {{ viewStore[label.key] ?? '-' }}
                </template>
              </dd>
            </div>
          </template>
        </dl>
      </div>
    </div>
  </StoreLayout>
</template>
