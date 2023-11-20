<script setup>
import { EMPLOYEE_REQUEST_FIELD_MAP } from '@/Constants/EmployeeRequest.js';

defineProps({
  employeeRequestForm: Object,
});
</script>

<template>
  <div class="flex flex-col gap-4 pb-8">
    <div class="flex flex-col gap-1" v-for="field in EMPLOYEE_REQUEST_FIELD_MAP.entries()">
      <template
        v-if="
          [
            'username',
            'commencement_date',
            'nric',
            'address_1',
            'gender',
            'date_of_birth',
            'emergency_name',
            'bank_name',
            'remarks',
          ].includes(field[0])
        "
      >
        <div class="divider pt-10"></div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'username'">Login Information</div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'commencement_date'">
          Main Information
        </div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'nric'">Contact Info</div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'address_1'">Location Information</div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'gender'">Ethnic Information</div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'date_of_birth'">
          Nationality Information
        </div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'emergency_name'">
          Emergency Information
        </div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'bank_name'">Bank Information</div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'remarks'">Other Information</div>
      </template>

      <label :for="field[0]" class="block text-sm font-medium leading-6 text-gray-900">{{ field[1] }}</label>
      <div class="flex flex-col gap-1">
        <input
          type="text"
          :id="field[0]"
          :name="field[0]"
          v-model="employeeRequestForm[field[0]]"
          class="input input-bordered w-full"
          :class="[employeeRequestForm.errors[field[0]] ? 'border-error' : '']"
          @input="() => employeeRequestForm.clearErrors(field[0])"
        />
        <span v-if="employeeRequestForm.errors[field[0]]" class="text-error">
          {{ employeeRequestForm.errors[field[0]] }}
        </span>
      </div>
    </div>
  </div>
</template>
