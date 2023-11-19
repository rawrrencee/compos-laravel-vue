<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import SupplierLayout from '@/Pages/Admin/Infrastructure/Suppliers/SupplierLayout.vue';
import { getImgSrcFromPath } from '@/Util/Photo';
import { PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';

const props = defineProps({
  errorMessage: String,
  supplier: Object | undefined,
});
const inputFields = [
  { key: 'supplier_name', title: 'Supplier Name' },
  { key: 'supplier_photo', title: 'Supplier Photo' },
  { key: 'active', title: 'Active' },
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
];
const supplierForm = useForm({
  supplier_name: props.supplier?.supplier_name ?? '',
  active: !props.supplier?.active ? false : true,
  address_1: props.supplier?.address_1 ?? '',
  address_2: props.supplier?.address_2 ?? '',
  email: props.supplier?.email ?? '',
  phone_number: props.supplier?.phone_number ?? '',
  mobile_number: props.supplier?.mobile_number ?? '',
  website: props.supplier?.website ?? '',
  img_url: props.supplier?.img_url ?? '',
  supplier_photo: null,
});
const photoPreview = ref(null);
const photoFile = ref(null);
const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: supplierForm.hasErrors && showFlashError.value,
    type: 'default',
    status: 'error',
    message: 'Please complete or correct the required fields.',
  };
});

const onAdminAlertButtonClicked = () => {
  showFlashError.value = false;
};
const updatePhotoPreview = (event) => {
  photoFile.value = event.target.files[0];
  if (!photoFile.value) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    photoPreview.value = e.target.result;
  };
  reader.readAsDataURL(photoFile.value);
};
const submit = () => {
  showFlashError.value = true;
  if (!props.supplier) {
    supplierForm
      .transform((data) => ({
        ...data,
        ...(photoFile.value && {
          supplier_photo: photoFile.value,
        }),
      }))
      .post(route('admin/infrastructure/suppliers/add.store'));
  } else {
    supplierForm
      .transform((data) => ({
        ...data,
        ...(photoFile.value && {
          supplier_photo: photoFile.value,
        }),
        id: props.supplier.id,
      }))
      .post(route('admin/infrastructure/suppliers/edit.update'));
  }
};
const deletePhoto = () => {
  if (props.supplier && confirm('Are you sure you want to remove this photo? It is not recoverable.')) {
    router.post(route('admin/infrastructure/suppliers/photo.delete'), {
      id: props.supplier.id,
      img_path: props.supplier.img_path,
    });
  }
};
</script>

<template>
  <SupplierLayout>
    <Head :title="`${!!supplier ? 'Edit' : 'Add New'} Supplier`" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <form class="flex h-full flex-col gap-x-6 gap-y-8 px-4 pt-4 sm:px-6 lg:px-8" @submit.prevent="submit">
      <div class="flex flex-grow flex-col gap-x-6 gap-y-8">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 pb-12 sm:grid-cols-6">
          <div class="sm:col-span-4" v-for="input in inputFields" :key="input.key">
            <template v-if="input.key === 'supplier_photo'">
              <label for="supplier_photo" class="block text-sm font-medium leading-6 text-gray-900"
                >Supplier Photo</label
              >
              <div
                class="mt-2 flex max-w-lg justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
              >
                <div class="flex flex-col justify-center gap-2 text-center">
                  <div v-if="!photoPreview" class="mt-2 flex flex-col items-center gap-4">
                    <img
                      v-if="supplier?.img_url || supplier?.img_path"
                      :src="supplier?.img_path ? getImgSrcFromPath(supplier?.img_path) : supplier?.img_url"
                      class="h-20 w-20 rounded-lg object-cover"
                    />
                    <PhotoIcon class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" v-else />
                    <button
                      type="button"
                      class="btn btn-ghost btn-sm"
                      v-if="supplier?.img_path"
                      :disabled="!!supplier?.img_url && !supplier?.img_path"
                      @click="deletePhoto"
                    >
                      <div class="flex items-center gap-2">
                        <XMarkIcon class="h-3 w-3" />
                        <span>Remove</span>
                      </div>
                    </button>
                    <span class="text-xs text-gray-600" v-if="supplier?.img_url && !supplier?.img_path"
                      >Image populated from Image URL (filled in below)</span
                    >
                  </div>
                  <div v-else class="self-center">
                    <span
                      class="block h-20 w-20 rounded-lg bg-cover bg-center bg-no-repeat"
                      :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-center text-sm leading-6">
                      <label
                        for="supplier_photo"
                        class="relative cursor-pointer rounded-md bg-white font-semibold text-primary focus-within:outline-none focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 hover:text-secondary"
                      >
                        <span
                          >Click to upload
                          {{ supplier?.img_path ? 'another' : 'a new' }}
                          file</span
                        >
                        <input
                          id="supplier_photo"
                          name="supplier_photo"
                          type="file"
                          class="sr-only"
                          @change="updatePhotoPreview"
                        />
                      </label>
                    </div>
                    <p class="text-xs leading-5 text-gray-600">PNG or JPG up to 10MB</p>
                  </div>
                </div>
              </div>
            </template>
            <template v-else-if="input.key === 'active'">
              <label for="active" class="block text-sm font-medium leading-6 text-gray-900">Active</label>
              <div class="mt-2">
                <input type="checkbox" name="active" class="toggle toggle-primary" v-model="supplierForm.active" />
              </div>
            </template>
            <template v-else>
              <label :for="input.key" class="block text-sm font-medium leading-6 text-gray-900">{{
                input.title
              }}</label>
              <div class="mt-2 flex flex-col gap-1">
                <input
                  type="text"
                  :id="input.key"
                  :name="input.key"
                  class="input input-bordered w-full max-w-lg"
                  v-model="supplierForm[input.key]"
                  :class="supplierForm.errors[input.key] ? 'border-error' : ''"
                  @input="() => supplierForm.clearErrors([input.key])"
                />
                <span v-if="supplierForm.errors[input.key]" class="text-error">
                  {{ supplierForm.errors[input.key] }}
                </span>
              </div>
            </template>
          </div>
        </div>
      </div>

      <StickyFooter back-url="admin/infrastructure/suppliers" />
    </form>
  </SupplierLayout>
</template>
