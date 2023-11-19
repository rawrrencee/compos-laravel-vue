<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { isCurrent } from '../../Util/Layout';

defineProps({
  navigation: Array,
});

const currentRoute = ref(route().current());
</script>

<template>
  <li v-for="item in navigation" :key="item.name">
    <Link
      :href="route(item.href)"
      :class="[
        isCurrent(item, currentRoute) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:bg-gray-200',
        'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700',
      ]"
    >
      <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
      <span class="sr-only">{{ item.name }}</span>
    </Link>
  </li>
</template>
