<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import GenericFormFields from '@/Components/Shared/GenericFormFields.vue';
import { COMPANY_CREATE_FORM_FIELDS } from '@/Constants/CompanyCreate';
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
    message: 'Please complete or correct the highlighted fields.',
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
      .post(route('infrastructure.companies.create'));
  } else {
    companyForm
      .transform((data) => ({
        ...data,
        ...(photoFile.value && {
          company_photo: photoFile.value,
        }),
        id: props.company.id,
      }))
      .post(route('infrastructure.companies.update'));
  }
};
const deletePhoto = () => {
  if (props.company && confirm('Are you sure you want to remove this photo? It is not recoverable.')) {
    router.post(route('infrastructure.companies.photo.delete'), {
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
    <form class="flex h-full flex-col gap-x-6 gap-y-8 px-4 pt-4 sm:px-6 lg:px-8" @submit.prevent="submit">
      <div class="flex flex-grow flex-col gap-x-6 gap-y-8">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 pb-12 sm:grid-cols-6">
          <div class="sm:col-span-4" v-for="field in COMPANY_CREATE_FORM_FIELDS.entries()" :key="field.key">
            <template v-if="field[0] === 'company_photo'">
              <label for="company_photo" class="block text-sm font-medium leading-6 text-gray-900">Company Photo</label>
              <div
                class="mt-2 flex max-w-lg justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
              >
                <div class="flex flex-col justify-center gap-2 text-center">
                  <div v-if="!photoPreview" class="mt-2 flex flex-col items-center gap-4">
                    <img
                      v-if="company?.img_url || company?.img_path"
                      :src="company?.img_path ? getImgSrcFromPath(company?.img_path) : company?.img_url"
                      class="h-20 w-20 rounded-lg object-cover"
                    />
                    <PhotoIcon class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" v-else />
                    <button
                      type="button"
                      class="btn btn-ghost btn-sm"
                      v-if="company?.img_path"
                      :disabled="!!company?.img_url && !company?.img_path"
                      @click="deletePhoto"
                    >
                      <div class="flex items-center gap-2">
                        <XMarkIcon class="h-3 w-3" />
                        <span>Remove</span>
                      </div>
                    </button>
                    <span class="text-xs text-gray-600" v-if="company?.img_url && !company?.img_path"
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
            <template v-else>
              <GenericFormFields
                :form="companyForm"
                :label="field[1].label"
                :name="field[1].name"
                :type="field[1].type"
              />
            </template>
          </div>
        </div>
      </div>

      <StickyFooter back-url="infrastructure.companies.viewLandingPage" />
    </form>
  </CompanyLayout>
</template>
