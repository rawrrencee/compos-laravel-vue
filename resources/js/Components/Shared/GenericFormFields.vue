<script setup>
import { CalendarIcon } from '@heroicons/vue/24/outline';
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import { ref } from 'vue';

defineProps({
  form: Object,
  type: String,
  name: String,
  label: String,
  disabled: Boolean,
  data: Object,
});

const popover = ref({
  visibility: 'focus',
});
</script>

<template>
  <label :for="name" class="block text-sm font-medium leading-6 text-gray-900">{{ label }}</label>
  <div class="flex flex-col gap-1">
    <template v-if="['email', 'number', 'password', 'text'].includes(type)">
      <input
        class="input input-bordered w-full [appearance:textfield] disabled:bg-gray-300 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
        :class="[form.errors[name] ? 'border-error' : '']"
        :disabled="disabled"
        :id="name"
        :name="name"
        :type="type"
        v-model="form[name]"
        @input="() => form.clearErrors(name)"
      />
    </template>
    <template v-else-if="type === 'dropdown'">
      <select
        class="select select-bordered w-full disabled:bg-gray-300"
        :class="form.errors[name] ? 'border-error' : ''"
        :disabled="disabled"
        :name="name"
        v-model="form[name]"
      >
        <template v-if="data?.dropdown?.disabledSelect">
          <option disabled selected>{{ data.dropdown.disabledSelect.label }}</option>
        </template>
        <option v-for="(opt, index) in data?.dropdown?.options ?? []" :key="opt.key ?? index" :value="opt.value">
          {{ opt.text }}
        </option>
      </select>
    </template>
    <template v-else-if="type === 'date'">
      <DatePicker v-model="form[name]" :input-debounce="500" :popover="popover">
        <template #default="{ inputValue, inputEvents }">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
              <CalendarIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </div>
            <input
              class="input join-item input-bordered w-full py-1.5 pl-10 disabled:bg-gray-300"
              :class="form.errors[name] ? 'border-error' : ''"
              :disabled="disabled"
              :value="inputValue"
              v-on="inputEvents"
            />
          </div>
        </template>
      </DatePicker>
    </template>
    <template v-else-if="type === 'textarea'">
      <textarea
        class="textarea textarea-bordered disabled:bg-gray-300"
        :class="form.errors[name] ? 'border-error' : ''"
        :disabled="disabled"
        :name="name"
        v-model="form[name]"
      ></textarea>
    </template>
    <span v-if="form.errors[name]" class="text-error">
      {{ form.errors[name] }}
    </span>
  </div>
</template>
