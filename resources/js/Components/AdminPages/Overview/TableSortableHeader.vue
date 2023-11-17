<script setup>
import { BarsArrowDownIcon, BarsArrowUpIcon } from '@heroicons/vue/24/outline';

defineProps({
  title: String,
  toggleSort: String,
});

defineEmits(['tableHeaderClicked']);
</script>

<template>
  <th scope="col" class="py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">
    <button type="button" class="flex gap-2" @click="$emit('tableHeaderClicked', title)">
      <span>{{ title }}</span>
      <BarsArrowUpIcon class="h-5 w-5 text-gray-400 hover:text-gray-700" v-if="toggleSort === 'asc'" />
      <BarsArrowDownIcon class="h-5 w-5 text-gray-400 hover:text-gray-700" v-if="toggleSort === 'desc'" />
    </button>
  </th>
</template>
