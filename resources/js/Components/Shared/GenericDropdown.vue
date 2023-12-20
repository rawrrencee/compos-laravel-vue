<script setup>
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxLabel,
  ComboboxOption,
  ComboboxOptions,
} from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/24/outline';
import { computed, ref } from 'vue';

const props = defineProps({
  form: Object,
  name: String,
  label: String,
  disabled: Boolean,
  data: Object,
});

const query = ref('');
const options = computed(() => {
  const dropdownOptions = props.data?.dropdown?.options ?? [];
  return query.value === ''
    ? dropdownOptions
    : dropdownOptions.filter((opt) => opt.text.toLowerCase().includes(query.value.toLowerCase()));
});
</script>

<template>
  <Combobox as="div" v-model="form[name]">
    <ComboboxLabel class="block text-sm font-medium leading-6 text-gray-900">{{ label }}</ComboboxLabel>
    <div class="relative mt-2">
      <ComboboxInput
        class="input input-bordered w-full"
        @change="
          ($event) => {
            form.clearErrors(name);
            query = $event.target.value;
          }
        "
        :display-value="(val) => options.find((opt) => opt.value === val)?.text"
      />
      <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
      </ComboboxButton>
      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-out"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <ComboboxOptions
          v-if="options.length > 0"
          class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
        >
          <ComboboxOption
            v-for="(opt, index) in options"
            :key="opt.key ?? index"
            :value="opt.value"
            as="template"
            v-slot="{ active, selected }"
          >
            <li
              :class="[
                'relative cursor-default select-none py-2 pl-3 pr-9',
                active ? 'bg-primary text-white' : 'text-gray-900',
              ]"
            >
              <span :class="['block truncate', selected && 'font-semibold']">
                {{ opt.text }}
              </span>

              <span
                v-if="selected"
                :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-white' : 'text-primary']"
              >
                <CheckIcon class="h-5 w-5" aria-hidden="true" />
              </span>
            </li>
          </ComboboxOption>
        </ComboboxOptions>
      </transition>
    </div>
  </Combobox>
</template>
