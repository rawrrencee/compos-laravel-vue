<script setup>
import { EMPLOYEE_REQUEST_FIELD_MAP } from '@/Constants/EmployeeRequest.js';
import GenericFormFields from './GenericFormFields.vue';

defineProps({
  employeeRequestForm: Object,
  authenticated: {
    type: Boolean,
    default: true,
  },
  countries: Array,
  genders: Array,
  identityTypes: Array,
  races: Array,
  residencyStatuses: Array,
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
            'phone_number',
            'address_1',
            'gender',
            'date_of_birth',
            'emergency_name',
            'bank_name',
            'remarks',
          ].includes(field[0])
        "
      >
        <div class="divider pt-4"></div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'username'">Login Information</div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'commencement_date'">
          Main Information
        </div>
        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'phone_number'">Contact Info</div>
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

      <template v-if="['country', 'nationality'].includes(field[0])">
        <GenericFormFields
          :data="{
            dropdown: {
              disabledSelect: {
                label: 'Select a country',
              },
              options: countries?.map((c) => ({
                key: c.num_code,
                value: c.alpha_3_code,
                text: field[0] === 'country' ? c.en_short_name : c.nationality,
              })),
            },
          }"
          :disabled="!authenticated"
          :form="employeeRequestForm"
          :label="field[1].label"
          :name="field[1].name"
          :type="field[1].type"
        />
      </template>
      <template v-else-if="['gender', 'race', 'identity_type', 'residency_status'].includes(field[0])">
        <GenericFormFields
          :data="{
            dropdown: {
              disabledSelect: {
                label: `Select a ${field[0].replace('_', ' ')}`,
              },
              options: (field[0] === 'gender'
                ? genders
                : field[0] === 'race'
                  ? races
                  : field[0] === 'identity_type'
                    ? identityTypes
                    : residencyStatuses
              )?.map((c) => ({
                key: c.key,
                value: c.key,
                text: c.value,
              })),
            },
          }"
          :disabled="!authenticated"
          :form="employeeRequestForm"
          :label="field[1].label"
          :name="field[1].name"
          :type="field[1].type"
        />
      </template>
      <template v-else>
        <GenericFormFields
          :disabled="!authenticated"
          :form="employeeRequestForm"
          :label="field[1].label"
          :name="field[1].name"
          :type="field[1].type"
        />
      </template>
    </div>
  </div>
</template>
