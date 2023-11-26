<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'commerce.brands.viewLandingPage',
    children: [
      {
        name: 'Add New Brand',
        href: 'commerce.brands.viewCreatePage',
      },
      {
        name: 'Edit Brand',
        href: 'commerce.brands.viewEditPageById',
      },
      {
        name: 'View Brand',
        href: 'commerce.brands.viewReadOnlyPageById',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">Brands</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
