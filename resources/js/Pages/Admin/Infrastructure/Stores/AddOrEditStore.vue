<script setup>
import StoreLayout from '@/Pages/Admin/Infrastructure/Stores/StoreLayout.vue';
import { getImgSrcFromPath } from '@/Util/Photo';
import { PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';

const props = defineProps({
  errorMessage: String,
  companies: Array,
  store: Object | undefined,
});
const inputFields = [
  { key: 'store_name', title: 'Store Name' },
  { key: 'store_code', title: 'Store Code', maxlength: 4 },
  { key: 'store_photo', title: 'Store Photo' },
  { key: 'active', title: 'Active' },
  { key: 'company_id', title: 'Company' },
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
  { key: 'tax_percentage', title: 'Tax Percentage', type: 'number' },
  { key: 'include_tax', title: 'Includes Tax' },
];
const storeForm = useForm({
  store_name: props.store?.store_name ?? '',
  store_code: props.store?.store_code ?? '',
  company_id: props.store?.company_id ?? '',
  address_1: props.store?.address_1 ?? '',
  address_2: props.store?.address_2 ?? '',
  email: props.store?.email ?? '',
  phone_number: props.store?.phone_number ?? '',
  mobile_number: props.store?.mobile_number ?? '',
  website: props.store?.website ?? '',
  active: props.store?.active === 0 ? false : true,
  include_tax: props.store?.include_tax === 0 ? false : true,
  tax_percentage: props.store?.tax_percentage ?? '',
  img_url: props.store?.img_url ?? '',
  store_photo: null,
});
const photoPreview = ref(null);
const photoFile = ref(null);
const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: storeForm.hasErrors && showFlashError.value,
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
  if (!props.store) {
    storeForm
      .transform((data) => ({
        ...data,
        store_code: data.store_code?.toLocaleUpperCase() ?? '',
        ...(photoFile.value && {
          store_photo: photoFile.value,
        }),
      }))
      .post(route('admin/infrastructure/stores/add.store'));
  } else {
    storeForm
      .transform((data) => ({
        ...data,
        store_code: data.store_code?.toLocaleUpperCase() ?? '',
        ...(photoFile.value && {
          store_photo: photoFile.value,
        }),
        id: props.store.id,
      }))
      .post(route('admin/infrastructure/stores/edit.update'));
  }
};
const deletePhoto = () => {
  if (props.store && confirm('Are you sure you want to remove this photo? It is not recoverable.')) {
    router.post(route('admin/infrastructure/stores/photo.delete'), {
      id: props.store.id,
      img_path: props.store.img_path,
    });
  }
};
</script>

<template>
  <StoreLayout>
    <Head :title="`${!!store ? 'Edit' : 'Add New'} Store`" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <div class="sm:px-6 lg:px-8">
      <form class="px-4 pt-4 sm:px-0 flex flex-col h-full" @submit.prevent="submit">
        <div class="flex-1 grow grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pb-12">
          <div class="sm:col-span-4" v-for="input in inputFields" :key="input.key">
            <template v-if="input.key === 'store_photo'">
              <label for="store_photo" class="block text-sm font-medium leading-6 text-gray-900">Store Photo</label>
              <div
                class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 max-w-lg"
              >
                <div class="text-center flex flex-col gap-2 justify-center">
                  <div v-if="!photoPreview" class="mt-2 flex flex-col gap-4 items-center">
                    <img
                      v-if="store?.img_url || store?.img_path"
                      :src="store?.img_path ? getImgSrcFromPath(store?.img_path) : store?.img_url"
                      class="rounded-lg h-20 w-20 object-cover"
                    />
                    <PhotoIcon class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" v-else />
                    <button
                      type="button"
                      class="btn btn-ghost btn-sm"
                      v-if="store?.img_path"
                      :disabled="!!store?.img_url && !store?.img_path"
                      @click="deletePhoto"
                    >
                      <div class="flex gap-2 items-center">
                        <XMarkIcon class="h-3 w-3" />
                        <span>Remove</span>
                      </div>
                    </button>
                    <span class="text-gray-600 text-xs" v-if="store?.img_url && !store?.img_path"
                      >Image populated from Image URL (filled in below)</span
                    >
                  </div>
                  <div v-else class="self-center">
                    <span
                      class="block rounded-lg w-20 h-20 bg-cover bg-no-repeat bg-center"
                      :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                  </div>
                  <div class="flex flex-col">
                    <div class="flex text-sm leading-6 justify-center">
                      <label
                        for="store_photo"
                        class="relative cursor-pointer rounded-md bg-white font-semibold text-primary focus-within:outline-none focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 hover:text-secondary"
                      >
                        <span
                          >Click to upload
                          {{ store?.img_path ? 'another' : 'a new' }}
                          file</span
                        >
                        <input
                          id="store_photo"
                          name="store_photo"
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
                <input type="checkbox" name="active" class="toggle toggle-primary" v-model="storeForm.active" />
              </div>
            </template>
            <template v-else-if="input.key === 'company_id'">
              <label for="company_id" class="block text-sm font-medium leading-6 text-gray-900">Company</label>
              <div class="mt-2 flex flex-col gap-1">
                <select
                  class="select select-bordered w-full max-w-lg"
                  :class="storeForm.errors[input.key] ? 'border-error' : ''"
                  name="company_id"
                  v-model="storeForm.company_id"
                >
                  <option disabled selected>Select a company</option>
                  <option v-for="company in props.companies" :key="company.id" :value="company.id">
                    {{ company.company_name }}
                  </option>
                </select>
                <span v-if="storeForm.errors[input.key]" class="text-error">
                  {{ storeForm.errors[input.key] }}
                </span>
              </div>
            </template>
            <template v-else-if="input.key === 'include_tax'">
              <label for="include_tax" class="block text-sm font-medium leading-6 text-gray-900"
                >Item Price Includes Tax</label
              >
              <div class="mt-2">
                <input
                  type="checkbox"
                  name="include_tax"
                  class="toggle toggle-primary"
                  v-model="storeForm.include_tax"
                />
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
                  v-model="storeForm[input.key]"
                  :class="[
                    storeForm.errors[input.key] ? 'border-error' : '',
                    input.key === 'store_code' && 'uppercase',
                  ]"
                  @input="() => storeForm.clearErrors([input.key])"
                />
                <span v-if="storeForm.errors[input.key]" class="text-error">
                  {{ storeForm.errors[input.key] }}
                </span>
              </div>
            </template>
          </div>
        </div>

        <div class="bottom-0 sticky py-4 bg-white border-t-2 border-gray-100">
          <div class="grid grid-cols-2 gap-2 sm:flex sm:justify-end sm:items-stretch">
            <Link type="button" class="btn sm:grow sm:max-w-[10rem]" :href="route('admin/infrastructure/stores')">
              Cancel
            </Link>
            <button type="submit" class="btn btn-primary sm:grow sm:max-w-[10rem]">Save</button>
          </div>
        </div>
      </form>
    </div>
  </StoreLayout>
</template>
