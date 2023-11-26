<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'infrastructure.stores.viewLandingPage',
    children: [
      {
        name: 'Add New Store',
        href: 'infrastructure.stores.viewCreatePage',
      },
      {
        name: 'Edit Store',
        href: 'infrastructure.stores.viewEditPageById',
      },
      {
        name: 'View Store',
        href: 'infrastructure.stores.viewReadOnlyPageById',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">Stores</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
