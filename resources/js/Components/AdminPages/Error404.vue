<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  show: Boolean,
});
</script>

<template>
  <div class="grid min-h-full place-items-center" v-if="show">
    <div class="flex flex-col justify-center text-center">
      <p class="text-base font-semibold text-primary">404</p>
      <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-5xl">Page not found</h1>
      <p class="mt-6 text-base leading-7 text-gray-600">Sorry, we couldn’t find the page you’re looking for.</p>
      <div class="mt-10 flex items-center justify-center">
        <Link :href="route('dashboard')" class="btn btn-primary">Dashboard</Link>
      </div>
    </div>
  </div>
</template>
