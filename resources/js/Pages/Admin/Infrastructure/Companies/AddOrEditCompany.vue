<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import CompanyLayout from '@/Pages/Admin/Infrastructure/Companies/CompanyLayout.vue';
import { getImgSrcFromPath } from '@/Util/Photo';
import { PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';

const props = defineProps({
  errorMessage: String,
  company: Object | undefined,
});
const inputFields = [
  { key: 'company_name', title: 'Company Name' },
  { key: 'company_photo', title: 'Company Photo' },
  { key: 'active', title: 'Active' },
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
];
const companyForm = useForm({
  company_name: props.company?.company_name ?? '',
  active: props.company?.active === 0 ? false : true,
  address_1: props.company?.address_1 ?? '',
  address_2: props.company?.address_2 ?? '',
  email: props.company?.email ?? '',
  phone_number: props.company?.phone_number ?? '',
  mobile_number: props.company?.mobile_number ?? '',
  website: props.company?.website ?? '',
  img_url: props.company?.img_url ?? '',
  company_photo: null,
});
const photoPreview = ref(null);
const photoFile = ref(null);
const showFlashError = ref(true);

const flashError = computed(() => {
  return {
    show: companyForm.hasErrors && showFlashError.value,
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
  if (!props.company) {
    companyForm
      .transform((data) => ({
        ...data,
        ...(photoFile.value && {
          company_photo: photoFile.value,
        }),
      }))
      .post(route('admin/infrastructure/companies/add.store'));
  } else {
    companyForm
      .transform((data) => ({
        ...data,
        ...(photoFile.value && {
          company_photo: photoFile.value,
        }),
        id: props.company.id,
      }))
      .post(route('admin/infrastructure/companies/edit.update'));
  }
};
const deletePhoto = () => {
  if (props.company && confirm('Are you sure you want to remove this photo? It is not recoverable.')) {
    router.post(route('admin/infrastructure/companies/photo.delete'), {
      id: props.company.id,
      img_path: props.company.img_path,
    });
  }
};
</script>

<template>
  <CompanyLayout>
    <Head :title="`${!!company ? 'Edit' : 'Add New'} Company`" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <form class="sm:px-6 lg:px-8 px-4 pt-4 flex flex-col gap-x-6 gap-y-8 h-full" @submit.prevent="submit">
      <div class="flex-grow flex flex-col gap-x-6 gap-y-8">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pb-12">
          <div class="sm:col-span-4" v-for="input in inputFields" :key="input.key">
            <template v-if="input.key === 'company_photo'">
              <label for="company_photo" class="block text-sm font-medium leading-6 text-gray-900">Company Photo</label>
              <div
                class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 max-w-lg"
              >
                <div class="text-center flex flex-col gap-2 justify-center">
                  <div v-if="!photoPreview" class="mt-2 flex flex-col gap-4 items-center">
                    <img
                      v-if="company?.img_url || company?.img_path"
                      :src="company?.img_path ? getImgSrcFromPath(company?.img_path) : company?.img_url"
                      class="rounded-lg h-20 w-20 object-cover"
                    />
                    <PhotoIcon class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" v-else />
                    <button
                      type="button"
                      class="btn btn-ghost btn-sm"
                      v-if="company?.img_path"
                      :disabled="!!company?.img_url && !company?.img_path"
                      @click="deletePhoto"
                    >
                      <div class="flex gap-2 items-center">
                        <XMarkIcon class="h-3 w-3" />
                        <span>Remove</span>
                      </div>
                    </button>
                    <span class="text-gray-600 text-xs" v-if="company?.img_url && !company?.img_path"
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
                        for="company_photo"
                        class="relative cursor-pointer rounded-md bg-white font-semibold text-primary focus-within:outline-none focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 hover:text-secondary"
                      >
                        <span
                          >Click to upload
                          {{ company?.img_path ? 'another' : 'a new' }}
                          file</span
                        >
                        <input
                          id="company_photo"
                          name="company_photo"
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
                <input type="checkbox" name="active" class="toggle toggle-primary" v-model="companyForm.active" />
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
                  v-model="companyForm[input.key]"
                  :class="companyForm.errors[input.key] ? 'border-error' : ''"
                  @input="() => companyForm.clearErrors([input.key])"
                />
                <span v-if="companyForm.errors[input.key]" class="text-error">
                  {{ companyForm.errors[input.key] }}
                </span>
              </div>
            </template>
          </div>
        </div>
      </div>

      <StickyFooter back-url="admin/infrastructure/companies" />
    </form>
  </CompanyLayout>
</template>
