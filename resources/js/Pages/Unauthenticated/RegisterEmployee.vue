<script setup>
import EmployeeRequestFormFields from '@/Components/Shared/EmployeeRequestFormFields.vue';
import LandingLayout from '@/Components/Unauthenticated/LandingLayout.vue';
import { EMPLOYEE_REQUEST_FIELD_MAP } from '@/Constants/EmployeeRequest';
import { KeyIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm } from '@inertiajs/vue3';

defineProps({
  companyName: String,
  authenticated: Boolean,
});

const organisationKeyForm = useForm({
  organisationKey: '',
});
const employeeRequestForm = useForm(
  Object.assign({}, ...Array.from(EMPLOYEE_REQUEST_FIELD_MAP, ([key]) => ({ [key]: '' })))
);

const submit = () => {
  console.log('submit', employeeRequestForm);
};

const checkOrganisationKey = () => {
  organisationKeyForm.post(route('unauth/register/employee-validate'), {
    only: ['authenticated'],
  });
};
</script>

<template>
  <main class="relative h-full bg-gray-800">
    <Head title="Register" />
    <LandingLayout :company-name="companyName">
      <form class="flex flex-col gap-12 px-6 py-12" @submit.prevent="submit">
        <div class="flex flex-col gap-4">
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
            <div class="flex flex-col gap-1">
              <input
                type="text"
                id="organisation_key"
                name="organisation_key"
                v-model="organisationKeyForm.organisationKey"
                class="input input-bordered w-full"
                :class="[organisationKeyForm.errors.organisationKey ? 'border-error' : '']"
                @input="() => organisationKeyForm.clearErrors('organisationKey')"
              />
              <span v-if="organisationKeyForm.errors.organisationKey" class="text-error">
                {{ organisationKeyForm.errors.organisationKey }}
              </span>
            </div>
            <button type="submit" class="btn btn-primary btn-sm mt-2" :disabled="organisationKeyForm.processing">
              Unlock
            </button>
          </form>
          <EmployeeRequestFormFields :employee-request-form="employeeRequestForm" :authenticated="authenticated" />
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
