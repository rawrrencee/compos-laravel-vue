<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  backUrl: String,
});
</script>

<template>
  <div class="bottom-0 sticky py-4 bg-white border-t-2 border-gray-100">
    <div class="grid grid-cols-2 gap-2 sm:flex sm:justify-end sm:items-stretch">
      <Link type="button" class="btn sm:grow sm:max-w-[10rem]" :href="route(backUrl)"> Cancel </Link>
      <button type="submit" class="btn btn-primary sm:grow sm:max-w-[10rem]">Save</button>
    </div>
  </div>
</template>
