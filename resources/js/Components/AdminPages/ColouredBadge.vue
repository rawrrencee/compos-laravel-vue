<script setup>
import { computed } from 'vue';

const props = defineProps({
  dataType: String,
  data: Number | Object | String,
});

const binarySpanColourMap = new Map([
  [false, 'bg-red-100 text-red-700'],
  [true, 'bg-green-100 text-green-700'],
]);

const enumSpanColourMap = new Map([
  ['APPROVED', 'bg-green-100 text-green-700'],
  ['PENDING', 'bg-gray-100 text-gray-700'],
  ['REJECTED', 'bg-red-100 text-red-700'],
]);

const spanColourClass = computed(() => {
  let colourCode;
  const defaultColour = 'bg-gray-100 text-gray-700';

  if (props.dataType) {
    switch (props.dataType) {
      case 'yesNo':
      case 'boolean':
        colourCode = binarySpanColourMap.get(!!props.data);
        break;
      case 'enum':
        colourCode = enumSpanColourMap.get(props.data);
        break;
      default:
        break;
    }
  }
  return colourCode ?? defaultColour;
});

const binaryFillColourMap = new Map([
  [false, 'fill-red-500'],
  [true, 'fill-green-500'],
]);

const enumFillColourMap = new Map([
  ['APPROVED', 'fill-green-500'],
  ['PENDING', 'fill-gray-500'],
  ['REJECTED', 'fill-red-500'],
]);

const svgColourClass = computed(() => {
  let colourCode;
  const defaultColour = 'fill-gray-500';

  if (props.dataType) {
    switch (props.dataType) {
      case 'yesNo':
      case 'boolean':
        colourCode = binaryFillColourMap.get(!!props.data);
        break;
      case 'enum':
        colourCode = enumFillColourMap.get(props.data);
        break;
      default:
        break;
    }
  }

  return colourCode ?? defaultColour;
});

const badgeTitle = computed(() => {
  if (props.dataType) {
    switch (props.dataType) {
      case 'yesNo':
        return !!props.data ? 'Yes' : 'No';
      case 'boolean':
        return !!props.data ? 'Active' : 'Inactive';
      default:
        return props.data;
    }
  }
});
</script>

<template>
  <div class="max-w-sm">
    <span class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium" :class="spanColourClass">
      <svg class="h-1.5 w-1.5" :class="svgColourClass" viewBox="0 0 6 6" aria-hidden="true">
        <circle cx="3" cy="3" r="3" />
      </svg>
      {{ badgeTitle }}
    </span>
  </div>
</template>
