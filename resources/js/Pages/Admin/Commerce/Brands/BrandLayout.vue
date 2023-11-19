<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'admin/commerce/brands',
    children: [
      {
        name: 'Add New Brand',
        href: 'admin/commerce/brands/add',
      },
      {
        name: 'Edit Brand',
        href: 'admin/commerce/brands/edit',
      },
      {
        name: 'View Brand',
        href: 'admin/commerce/brands/view',
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
