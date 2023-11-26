<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'infrastructure.suppliers.viewLandingPage',
    children: [
      {
        name: 'Add New Supplier',
        href: 'infrastructure.suppliers.viewCreatePage',
      },
      {
        name: 'Edit Supplier',
        href: 'infrastructure.suppliers.viewEditPageById',
      },
      {
        name: 'View Supplier',
        href: 'infrastructure.suppliers.viewReadOnlyPageById',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">Suppliers</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
