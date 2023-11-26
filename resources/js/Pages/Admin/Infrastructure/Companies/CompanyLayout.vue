<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'infrastructure.companies.viewLandingPage',
    children: [
      {
        name: 'Add New Company',
        href: 'infrastructure.companies.viewCreatePage',
      },
      {
        name: 'Edit Company',
        href: 'infrastructure.companies.viewEditPageById',
      },
      {
        name: 'View Company',
        href: 'infrastructure.companies.viewReadOnlyPageById',
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
