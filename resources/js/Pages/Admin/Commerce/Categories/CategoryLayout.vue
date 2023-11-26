<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'commerce.categories.viewLandingPage',
    children: [
      {
        name: 'Add New Category',
        href: 'commerce.categories.viewCreatePage',
      },
      {
        name: 'Edit Category',
        href: 'commerce.categories.viewEditPageById',
      },
      {
        name: 'View Category',
        href: 'commerce.categories.viewReadOnlyPageById',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">Categories</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
