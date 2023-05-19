<script setup>
import { PencilIcon, PlusCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  selectedItems: Array,
  addNewUrl: String,
  showEditDeleteBtn: Boolean,
});

defineEmits(['buttonClicked']);
</script>

<template>
  <div class="bottom-0 sticky py-4 px-4 sm:hidden z-40">
    <div class="grid grid-cols-2 gap-2" v-if="showEditDeleteBtn">
      <button type="button" class="btn btn-block btn-primary" @click="$emit('buttonClicked', { action: 'edit' })">
        <div class="flex gap-2 items-center">
          <PencilIcon class="w-5 h-5" />
          <span>Edit ({{ selectedItems.length }})</span>
        </div>
      </button>
      <button type="button" class="btn btn-block btn-error" @click="$emit('buttonClicked', { action: 'delete' })">
        <div class="flex gap-2 items-center">
          <XMarkIcon class="w-5 h-5" />
          <span>Delete ({{ selectedItems.length }})</span>
        </div>
      </button>
    </div>
    <Link as="button" :href="route(addNewUrl)" class="btn btn-block" v-else>
      <div class="flex gap-2 items-center">
        <PlusCircleIcon class="w-5 h-5" />
        <span>Add New</span>
      </div>
    </Link>
  </div>
</template>
