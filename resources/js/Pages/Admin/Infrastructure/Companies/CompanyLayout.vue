<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'admin/infrastructure/companies',
    children: [
      {
        name: 'Add New Company',
        href: 'admin/infrastructure/companies/add',
      },
      {
        name: 'Edit Company',
        href: 'admin/infrastructure/companies/edit',
      },
      {
        name: 'View Company',
        href: 'admin/infrastructure/companies/view',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">Companies</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
