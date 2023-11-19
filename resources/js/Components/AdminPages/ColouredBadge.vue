<script setup>
import { computed } from 'vue';

const props = defineProps({
  dataType: String,
  data: Number | Object | String,
});

const binaryColourMap = new Map([
  [false, 'red'],
  [true, 'green'],
]);

const enumColourMap = new Map([
  ['APPROVED', 'green'],
  ['PENDING', 'gray'],
  ['REJECTED', 'red'],
]);

const spanColourClass = computed(() => {
  let colourCode;
  const defaultColour = 'bg-gray-100 text-gray-700';

  if (props.dataType) {
    switch (props.dataType) {
      case 'yesNo':
      case 'boolean':
        colourCode = binaryColourMap.get(!!props.data);
        break;
      case 'enum':
        colourCode = enumColourMap.get(props.data);
        break;
      default:
        break;
    }
  }
  return colourCode ? `bg-${colourCode}-100 text-${colourCode}-700` : defaultColour;
});

const svgColourClass = computed(() => {
  let colourCode;
  const defaultColour = 'fill-gray-500';

  if (props.dataType) {
    switch (props.dataType) {
      case 'yesNo':
      case 'boolean':
        colourCode = binaryColourMap.get(!!props.data);
        break;
      case 'enum':
        colourCode = enumColourMap.get(props.data);
        break;
      default:
        break;
    }
  }

  return colourCode ? `fill-${colourCode}-500` : defaultColour;
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
      <svg class="h-1.5 w-1.5 fill-green-500" :class="svgColourClass" viewBox="0 0 6 6" aria-hidden="true">
        <circle cx="3" cy="3" r="3" />
      </svg>
      {{ badgeTitle }}
    </span>
  </div>
</template>
