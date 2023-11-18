<script setup>
import EmployeesLayout from '@/Pages/Admin/Users/Employees/EmployeesLayout.vue';
import { LockClosedIcon, PencilIcon } from '@heroicons/vue/24/outline';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  employeeRequest: Object,
});
const employeeRequestForm = useForm({
  global_value: props.employeeRequest?.global_value ?? '',
});

const editEmployeeRequestKey = ref(false);
const isLoading = ref(false);

const onEditEmployeeRequestSaveClicked = () => {
  employeeRequestForm.post(route('admin/users/employees/requests.key-update'), {
    onStart: () => (isLoading.value = true),
    onFinish: () => {
      isLoading.value = false;
      if (usePage().props.flash?.status !== 'error') {
        editEmployeeRequestKey.value = false;
      }
    },
  });
};
</script>

<template>
  <EmployeesLayout>
    <Head title="Employee Requests" />
    <div class="h-full flex flex-col sm:px-6 lg:px-8 gap-4">
      <div class="py-4 px-6 sm:px-0 flex flex-col gap-2">
        <label for="employee_request" class="block text-md font-semibold leading-6 text-gray-900"
          >Employee Request Key</label
        >
        <small class="text-secondary font-medium"
          >This is the key required for submitting an Employee Request to create a new Employee account.</small
        >
        <form
          class="flex flex-col gap-2"
          v-if="editEmployeeRequestKey"
          @submit.prevent="onEditEmployeeRequestSaveClicked"
        >
          <div class="flex flex-col gap-1">
            <input
              id="employee_request"
              type="text"
              name="employee_request"
              class="input join-item input-bordered input-sm w-full max-w-md"
              :class="employeeRequestForm.errors?.global_value ? 'border-error' : ''"
              v-model="employeeRequestForm.global_value"
            />
            <span v-if="employeeRequestForm.errors?.global_value" class="text-error">
              {{ employeeRequestForm.errors.global_value }}
            </span>
          </div>
          <div class="flex flex-row gap-2">
            <button type="submit" class="btn btn-sm btn-primary grow max-w-[8rem]">Save</button
            ><button type="button" class="btn btn-sm grow max-w-[8rem]" @click="editEmployeeRequestKey = false">
              Cancel
            </button>
          </div>
        </form>
        <template v-else>
          <div class="flex flex-col gap-2 sm:flex-row sm:gap-5">
            <div class="flex flex-row items-center gap-4 text-sm font-normal leading-6 text-gray-900">
              <LockClosedIcon class="w-3 h-3" />
              <span> {{ employeeRequest?.global_value ?? 'Not Set' }}</span>
            </div>
            <button type="button" class="btn btn-sm sm:btn-xs btn-primary" @click="editEmployeeRequestKey = true">
              <div class="flex gap-2 items-center">
                <PencilIcon class="w-3 h-3" />
                <span>Edit</span>
              </div>
            </button>
          </div>
        </template>
      </div>
      <div class="flex flex-col gap-2">
        <span class="px-6 sm:px-0 block text-md font-semibold leading-6 text-gray-900">Employee Requests</span>
        <table class="table">
          <!-- head -->
          <thead>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Job</th>
              <th>Favorite Color</th>
            </tr>
          </thead>
          <tbody>
            <!-- row 1 -->
            <tr class="bg-base-200">
              <th>1</th>
              <td>Cy Ganderton</td>
              <td>Quality Control Specialist</td>
              <td>Blue</td>
            </tr>
            <!-- row 2 -->
            <tr>
              <th>2</th>
              <td>Hart Hagerty</td>
              <td>Desktop Support Technician</td>
              <td>Purple</td>
            </tr>
            <!-- row 3 -->
            <tr>
              <th>3</th>
              <td>Brice Swyre</td>
              <td>Tax Accountant</td>
              <td>Red</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </EmployeesLayout>
</template>
