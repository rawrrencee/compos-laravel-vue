<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'admin/commerce/categories',
    children: [
      {
        name: 'Add New Category',
        href: 'admin/commerce/categories/add',
      },
      {
        name: 'Edit Category',
        href: 'admin/commerce/categories/edit',
      },
      {
        name: 'View Category',
        href: 'admin/commerce/categories/view',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-900 leading-tight">Categories</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
