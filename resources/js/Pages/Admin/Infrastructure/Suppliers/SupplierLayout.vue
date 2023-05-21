<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'admin/infrastructure/suppliers',
    children: [
      {
        name: 'Add New Supplier',
        href: 'admin/infrastructure/suppliers/add',
      },
      {
        name: 'Edit Supplier',
        href: 'admin/infrastructure/suppliers/edit',
      },
      {
        name: 'View Supplier',
        href: 'admin/infrastructure/suppliers/view',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-900 leading-tight">Suppliers</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
