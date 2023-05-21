<script setup>
import SupplierLayout from '@/Pages/Admin/Infrastructure/Suppliers/SupplierLayout.vue';
import { openInNewWindow } from '@/Util/Common';
import { getImgSrcFromPath } from '@/Util/Photo';
import { PhotoIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';
import ColouredBadge from '../../../../Components/AdminPages/ColouredBadge.vue';

const props = defineProps({
  viewSupplier: Object,
});

const moduleUrl = 'admin/infrastructure/suppliers';
const editUrl = `${moduleUrl}/edit`;
const viewSupplierLabels = [
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
];

const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: !!props.viewSupplier.deleted_at && showFlashError.value,
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
  <SupplierLayout>
    <Head title="View Supplier" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <div class="h-full divide-y divide-gray-200">
      <div class="pb-6">
        <div class="h-24 bg-primary sm:h-20 lg:h-28" />
        <div class="lg:-mt-15 -mt-12 flow-root px-4 sm:-mt-8 sm:flex sm:items-end sm:px-6">
          <div>
            <div class="-m-1 flex">
              <div class="inline-flex overflow-hidden rounded-lg border-4 border-white">
                <img
                  v-if="viewSupplier?.img_url || viewSupplier?.img_path"
                  :src="viewSupplier?.img_path ? getImgSrcFromPath(viewSupplier?.img_path) : viewSupplier?.img_url"
                  class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48 object-cover"
                />
                <PhotoIcon
                  class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48 text-gray-300 bg-white"
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
                  {{ viewSupplier.supplier_name }}
                </h3>
                <ColouredBadge :data="viewSupplier.active" data-type="boolean" />
              </div>
            </div>
            <div v-if="!viewSupplier.deleted_at" class="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
              <Link
                :href="route(editUrl, { id: viewSupplier.id })"
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
          <template v-for="label in viewSupplierLabels" :key="label.key">
            <div class="sm:flex sm:px-6 sm:py-5">
              <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                {{ label.title }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                <template v-if="['img_url', 'website'].includes(label.key)">
                  <a
                    :href="viewSupplier[label.key]"
                    v-if="viewSupplier[label.key]"
                    class="link break-all"
                    @click.prevent="openInNewWindow(viewSupplier[label.key])"
                    >{{ viewSupplier[label.key] }}</a
                  >
                  <span v-else>Unavailable</span>
                </template>
                <template v-else>
                  {{ viewSupplier[label.key] ?? 'Unavailable' }}
                </template>
              </dd>
            </div>
          </template>
        </dl>
      </div>
    </div>
  </SupplierLayout>
</template>
