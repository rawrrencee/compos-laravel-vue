<script setup>
import CompanyLayout from '@/Pages/Admin/Infrastructure/Companies/CompanyLayout.vue';
import { openInNewWindow } from '@/Util/Common';
import { getImgSrcFromPath } from '@/Util/Photo';
import { ArrowRightOnRectangleIcon, PhotoIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';
import ColouredBadge from '../../../../Components/AdminPages/ColouredBadge.vue';

const props = defineProps({
  viewCompany: Object,
});
console.log(props.viewCompany);

const moduleUrl = 'admin/infrastructure/companies';
const editUrl = `${moduleUrl}/edit`;
const viewCompanyLabels = [
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
  { key: 'stores', title: 'Stores' },
];

const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: !!props.viewCompany.deleted_at && showFlashError.value,
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
  <CompanyLayout>
    <Head title="View Company" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <div class="h-full divide-y divide-gray-200">
      <div class="pb-6">
        <div class="h-24 bg-primary sm:h-20 lg:h-28" />
        <div class="lg:-mt-15 -mt-12 flow-root px-4 sm:-mt-8 sm:flex sm:items-end sm:px-6">
          <div>
            <div class="-m-1 flex">
              <div
                class="h-24 w-24 sm:h-40 sm:w-40 lg:h-48 lg:w-48 flex items-center justify-center bg-white rounded-lg shadow-md border-4 border-white"
              >
                <img
                  v-if="viewCompany?.img_url || viewCompany?.img_path"
                  :src="viewCompany?.img_path ? getImgSrcFromPath(viewCompany?.img_path) : viewCompany?.img_url"
                  class="w-full h-auto"
                />
                <PhotoIcon
                  class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48 text-gray-300"
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
                  {{ viewCompany.company_name }}
                </h3>
                <ColouredBadge :data="viewCompany.active" data-type="boolean" />
              </div>
            </div>
            <div v-if="!viewCompany.deleted_at" class="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
              <Link
                :href="route(editUrl, { id: viewCompany.id })"
                as="button"
                class="btn btn-sm btn-block btn-primary sm:max-w-[7rem]"
              >
                Edit
              </Link>
            </div>
          </div>
        </div>
      </div>
      <div class="px-4 py-5 sm:px-0 sm:py-0">
        <dl class="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
          <template v-for="label in viewCompanyLabels" :key="label.key">
            <div class="sm:flex sm:px-6 sm:py-5">
              <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                {{ label.title }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                <template v-if="['img_url', 'website'].includes(label.key)">
                  <a
                    :href="viewCompany[label.key]"
                    v-if="viewCompany[label.key]"
                    class="link break-all"
                    @click.prevent="openInNewWindow(viewCompany[label.key])"
                    >{{ viewCompany[label.key] }}</a
                  >
                  <span v-else>-</span>
                </template>
                <template v-else-if="label.key === 'stores'">
                  <ul v-if="viewCompany.stores?.length > 0">
                    <li v-for="store in viewCompany.stores" class="m-1">
                      <Link
                        class="link flex items-center gap-1"
                        :href="route('admin/infrastructure/stores/view', { id: store.id })"
                      >
                        <span>{{ store.store_name }}&nbsp;({{ store.store_code }})</span>
                        <ArrowRightOnRectangleIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                      </Link>
                    </li>
                  </ul>
                  <template v-else> - </template>
                </template>
                <template v-else>
                  {{ viewCompany[label.key] ?? '-' }}
                </template>
              </dd>
            </div>
          </template>
        </dl>
      </div>
    </div>
  </CompanyLayout>
</template>
