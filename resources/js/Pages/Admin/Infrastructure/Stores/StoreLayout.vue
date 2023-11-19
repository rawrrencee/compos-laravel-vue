<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'admin/infrastructure/stores',
    children: [
      {
        name: 'Add New Store',
        href: 'admin/infrastructure/stores/add',
      },
      {
        name: 'Edit Store',
        href: 'admin/infrastructure/stores/edit',
      },
      {
        name: 'View Store',
        href: 'admin/infrastructure/stores/view',
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
