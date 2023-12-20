<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  backUrl: String,
  cancelButtonType: {
    type: String,
    default: 'route',
  },
  cancelButtonText: {
    type: String,
    default: 'Cancel',
  },
  saveButtonType: {
    type: String,
    default: 'submit',
  },
  saveButtonText: {
    type: String,
    default: 'Save',
  },
  showSaveButton: {
    type: Boolean,
    default: true,
  },
});

defineEmits(['cancelClicked', 'saveClicked']);
</script>

<template>
  <div class="sticky bottom-0 border-t-2 border-gray-100 bg-white py-4">
    <div class="grid grid-cols-2 gap-2 sm:flex sm:items-stretch sm:justify-end">
      <template v-if="cancelButtonType === 'route'">
        <Link type="button" class="btn sm:max-w-[10rem] sm:grow" :href="route(backUrl)"> {{ cancelButtonText }} </Link>
      </template>
      <template v-else-if="cancelButtonType === 'button'">
        <button type="button" class="btn sm:max-w-[10rem] sm:grow" @click="$emit('cancelClicked')">
          {{ cancelButtonText }}
        </button>
      </template>
      <button
        v-if="showSaveButton"
        :type="saveButtonType"
        class="btn btn-primary sm:max-w-[10rem] sm:grow"
        @click="saveButtonType === 'button' && $emit('saveClicked')"
      >
        {{ saveButtonText }}
      </button>
    </div>
  </div>
</template>
