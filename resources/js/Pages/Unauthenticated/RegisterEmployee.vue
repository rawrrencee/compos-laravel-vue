<script setup>
import AdminAlert from '@/Components/AdminLayout/AdminAlert.vue';
import EmployeeRequestFormFields from '@/Components/Shared/EmployeeRequestFormFields.vue';
import LandingLayout from '@/Components/Unauthenticated/LandingLayout.vue';
import { EMPLOYEE_REQUEST_FIELD_MAP } from '@/Constants/EmployeeRequest';
import { ArrowLeftIcon, CheckCircleIcon, KeyIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
  companyName: String,
  authenticated: Boolean,
  countries: Array,
  genders: Array,
  races: Array,
  residencyStatuses: Array,
});
const showFlashError = ref(true);

const organisationKeyForm = useForm({
  organisationKey: usePage().props.organisationKey ?? '',
});
const employeeRequestForm = useForm(
  Object.assign({}, ...Array.from(EMPLOYEE_REQUEST_FIELD_MAP, ([key]) => ({ [key]: '' })))
);
const submit = () => {
  showFlashError.value = true;
  employeeRequestForm
    .transform((data) => ({
      ...data,
      organisationKey: organisationKeyForm.organisationKey,
    }))
    .post(route('unauth/register/employee/submit'));
};
const checkOrganisationKey = () => {
  showFlashError.value = true;
  organisationKeyForm.get(route('unauth/register/employee'));
};
const resetPage = () => {
  router.visit(route('unauth/register/employee'));
};
</script>

<template>
  <main class="relative h-full bg-gray-800">
    <Head title="Register" />
    <LandingLayout :company-name="companyName">
      <AdminAlert v-if="$page.props.flash.type === 'default'" :flash="$page.props.flash" @button-clicked="resetPage" />
      <form class="flex flex-col gap-12 px-6 py-6" @submit.prevent="submit">
        <div class="flex flex-col gap-4">
          <div>
            <button type="button" class="btn btn-link pl-0" @click="router.get(route('unauth/login'))">
              <ArrowLeftIcon class="h-3 w-3 text-primary" /><span>Back</span>
            </button>
          </div>
          <div class="flex flex-row items-center gap-2">
            <UserGroupIcon class="h-6 w-6" />
            <span class="block text-xl font-black leading-7 text-gray-900">Employee Registration</span>
          </div>
          <small class="text-gray-600"
            >Please fill up the form below. Your details will be reviewed and your employee account will be created once
            it has been approved. You will receive your account details via email.</small
          >
        </div>

        <div class="flex flex-col gap-4">
          <form class="flex flex-col gap-2" @submit.prevent="checkOrganisationKey">
            <label
              for="organisation_key"
              class="flex flex-row items-center gap-2 text-sm font-medium leading-6 text-gray-900"
            >
              <KeyIcon class="h-4 w-4" />
              <span>Organisation Key</span>
            </label>
            <div class="flex flex-col gap-1" v-if="!authenticated">
              <input
                type="text"
                id="organisation_key"
                name="organisation_key"
                v-model="organisationKeyForm.organisationKey"
                class="input input-bordered w-full"
                :class="[organisationKeyForm.errors.organisationKey ? 'border-error' : '']"
                :disabled="authenticated"
                @input="() => organisationKeyForm.clearErrors('organisationKey')"
              />
              <span v-if="organisationKeyForm.errors.organisationKey" class="text-error">
                {{ organisationKeyForm.errors.organisationKey }}
              </span>
            </div>
            <div class="flex flex-row items-center gap-2 text-sm font-medium leading-6" v-else>
              <span>{{ organisationKeyForm.organisationKey }}</span>
              <CheckCircleIcon class="h-6 w-6 text-success" />
            </div>
            <button
              type="submit"
              class="btn btn-primary btn-sm mt-2"
              :disabled="organisationKeyForm.processing || authenticated"
              v-if="!authenticated"
            >
              Unlock
            </button>
          </form>
          <EmployeeRequestFormFields
            :employee-request-form="employeeRequestForm"
            :authenticated="authenticated"
            :countries="countries"
            :genders="genders"
            :races="races"
            :residency-statuses="residencyStatuses"
          />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <button type="button" class="btn btn-secondary text-gray-200" @click="router.get(route('unauth/login'))">
            Back
          </button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </LandingLayout>
  </main>
</template>
