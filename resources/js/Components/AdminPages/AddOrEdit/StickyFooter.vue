<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  backUrl: String,
});
</script>

<template>
  <div class="sticky bottom-0 border-t-2 border-gray-100 bg-white py-4">
    <div class="grid grid-cols-2 gap-2 sm:flex sm:items-stretch sm:justify-end">
      <Link type="button" class="btn sm:max-w-[10rem] sm:grow" :href="route(backUrl)"> Cancel </Link>
      <button type="submit" class="btn btn-primary sm:max-w-[10rem] sm:grow">Save</button>
    </div>
  </div>
</template>
