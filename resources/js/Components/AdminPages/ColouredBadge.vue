<script setup>
import { computed } from 'vue';

const props = defineProps({
  dataType: String,
  data: Number | Object | String,
});

const spanColourClass = computed(() => {
  const defaultColour = 'bg-gray-100 text-gray-600';
  const errorColour = 'bg-red-100 text-red-700';
  const successColour = 'bg-green-100 text-green-700';
  if (props.dataType) {
    switch (props.dataType) {
      case 'boolean':
        return !!props.data ? successColour : errorColour;
      default:
        break;
    }
  }
  return defaultColour;
});

const svgColourClass = computed(() => {
  const defaultColour = 'fill-gray-400';
  const errorColour = 'fill-red-500';
  const successColour = 'fill-green-500';
  if (props.dataType) {
    switch (props.dataType) {
      case 'boolean':
        return !!props.data ? successColour : errorColour;
      default:
        break;
    }
  }
  return defaultColour;
});

const badgeTitle = computed(() => {
  if (props.dataType) {
    switch (props.dataType) {
      case 'boolean':
        return !!props.data ? 'Active' : 'Inactive';
      default:
        break;
    }
  }
});
</script>

<template>
  <span class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium" :class="spanColourClass">
    <svg class="h-1.5 w-1.5 fill-green-500" :class="svgColourClass" viewBox="0 0 6 6" aria-hidden="true">
      <circle cx="3" cy="3" r="3" />
    </svg>
    {{ badgeTitle }}
  </span>
</template>
