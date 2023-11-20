<script setup>
import EmployeeRequestFormFields from '@/Components/Shared/EmployeeRequestFormFields.vue';
import LandingLayout from '@/Components/Unauthenticated/LandingLayout.vue';
import { EMPLOYEE_REQUEST_FIELD_MAP } from '@/Constants/EmployeeRequest';
import { KeyIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm } from '@inertiajs/vue3';

defineProps({
  companyName: String,
});

const employeeRequestForm = useForm(
  Object.assign({}, ...[...Array.from(EMPLOYEE_REQUEST_FIELD_MAP, ([key]) => ({ [key]: '' })), { organisationKey: '' }])
);
</script>

<template>
  <main class="relative h-full bg-gray-800">
    <Head title="Register" />
    <LandingLayout :company-name="companyName">
      <div class="flex flex-col gap-12 px-6 py-12">
        <div class="flex flex-col gap-4">
          <div class="flex flex-row items-center gap-4">
            <UserGroupIcon class="h-7 w-7" />
            <span class="block text-3xl font-black leading-7 text-gray-900">Employee Registration</span>
          </div>
          <small class="text-gray-600"
            >Please fill up the form below. Your details will be reviewed and your employee account will be created once
            it has been approved. You will receive your account details via email.</small
          >
        </div>

        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-2">
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
                v-model="employeeRequestForm.organisationKey"
                class="input input-bordered w-full"
                :class="[employeeRequestForm.errors.organisationKey ? 'border-error' : '']"
                @input="() => employeeRequestForm.clearErrors('organisationKey')"
              />
              <span v-if="employeeRequestForm.errors.organisationKey" class="text-error">
                {{ employeeRequestForm.errors.organisationKey }}
              </span>
            </div>
          </div>
          <EmployeeRequestFormFields :employee-request-form="employeeRequestForm" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <button type="button" class="btn btn-secondary text-gray-200" @click="router.get(route('unauth/login'))">
            Back
          </button>
          <button type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </LandingLayout>
  </main>
</template>
