<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import ColouredBadge from '@/Components/AdminPages/ColouredBadge.vue';
import EmployeeRequestFormFields from '@/Components/Shared/EmployeeRequestFormFields.vue';
import GenericDropdown from '@/Components/Shared/GenericDropdown.vue';
import { EMPLOYEE_REQUEST_FIELD_MAP } from '@/Constants/EmployeeRequest';
import { EMPLOYEE_REQUEST_STATUS } from '@/Constants/EmployeeRequestStatus';
import { PencilIcon } from '@heroicons/vue/24/outline';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import EmployeesLayout from './EmployeesLayout.vue';

// #region Page Variables
const props = defineProps({
  employeeRequestStatuses: Array,
  viewEmployeeRequest: Object,
  countries: Array,
  genders: Array,
  identityTypes: Array,
  races: Array,
  residencyStatuses: Array,
});

const employeeRequestForm = useForm(
  Object.assign(
    {},
    ...[
      ...Array.from(EMPLOYEE_REQUEST_FIELD_MAP, ([key]) => ({ [key]: props.viewEmployeeRequest?.[key]?.trim() ?? '' })),
      { username: props.viewEmployeeRequest?.username?.replace(/\s+/g, '') ?? '' },
      { status: props.viewEmployeeRequest?.status },
      { change_password: false },
    ]
  )
);

const onEditEmployeeRequestSaveClicked = () => {
  const requiresConfirmation =
    props.viewEmployeeRequest.status !== EMPLOYEE_REQUEST_STATUS.APPROVED &&
    employeeRequestForm.status === EMPLOYEE_REQUEST_STATUS.APPROVED;

  let isActionConfirmed = false;
  if (
    requiresConfirmation &&
    confirm(
      "Please confirm the employee's details as a new account will be created once the Employee Request is approved."
    )
  ) {
    isActionConfirmed = true;
  } else if (!requiresConfirmation) {
    isActionConfirmed = true;
  }

  if (isActionConfirmed) {
    employeeRequestForm
      .transform((data) => ({ ...data, id: props.viewEmployeeRequest?.id }))
      .post(route('users.employees.requests.update'), {
        onStart: () => {
          isLoading.value = true;
        },
        onFinish: () => {
          isLoading.value = false;
        },
      });
  }
};

// #endregion Page Variables

// #region Ref Variables
const isLoading = ref(false);
const isEditingRequestStatus = ref(false);
// #endregion Ref Variables
</script>

<template>
  <EmployeesLayout>
    <Head title="View Employee Request" />
    <form
      class="flex h-full flex-col gap-x-6 gap-y-8 px-4 pt-4 sm:px-6 lg:px-8"
      @submit.prevent="onEditEmployeeRequestSaveClicked"
    >
      <div class="flex flex-grow flex-col gap-x-6 gap-y-2">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 pb-12 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <template v-if="isEditingRequestStatus">
              <GenericDropdown
                :form="employeeRequestForm"
                name="status"
                label="Status"
                :disabled="false"
                :data="{
                  dropdown: {
                    disabledSelect: {
                      label: `Select a status`,
                    },
                    options: employeeRequestStatuses?.map((c, i) => ({
                      key: i,
                      value: c,
                      text: c,
                    })),
                  },
                }"
              />
            </template>
            <template v-else>
              <span class="text-md font-semibold leading-6 text-gray-900">Status</span>
              <div class="flex flex-row items-center gap-4">
                <ColouredBadge data-type="enum" :data="viewEmployeeRequest?.status" />
                <button type="button" class="btn btn-outline btn-xs" @click="isEditingRequestStatus = true">
                  <div class="flex items-center gap-2">
                    <PencilIcon class="h-3 w-3" />
                    <span>Edit</span>
                  </div>
                </button>
              </div>
            </template>
            <EmployeeRequestFormFields
              :employee-request-form="employeeRequestForm"
              :is-logged-in="true"
              :countries="countries"
              :genders="genders"
              :races="races"
              :identity-types="identityTypes"
              :residency-statuses="residencyStatuses"
            />
          </div>
        </div>
      </div>

      <StickyFooter
        back-url="users.employees.requests.viewLandingPage"
        :show-save-button="viewEmployeeRequest.status !== EMPLOYEE_REQUEST_STATUS.APPROVED"
      />
    </form>
  </EmployeesLayout>
</template>
