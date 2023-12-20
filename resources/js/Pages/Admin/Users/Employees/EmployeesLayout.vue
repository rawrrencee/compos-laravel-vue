<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'users.employees.viewLandingPage',
    children: [
      {
        name: 'Add New Employee',
        href: 'users.employees.viewCreatePage',
      },
    ],
  },
  {
    name: 'Contracts',
    href: 'users.employees.contracts.viewLandingPage',
  },
  {
    name: 'Insurance',
    href: 'users.employees.insurance.viewLandingPage',
  },
  {
    name: 'Designations',
    href: 'users.employees.designations.viewLandingPage',
  },
  {
    name: 'Permissions',
    href: 'users.employees.permissions.viewLandingPage',
  },
  {
    name: 'Requests',
    href: 'users.employees.requests.viewLandingPage',
    children: [
      {
        name: 'View Request',
        href: 'users.employees.requests.viewById',
      },
    ],
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-900">Employees</h2>
    </template>

    <div class="h-full">
      <slot />
    </div>
  </AdminLayout>
</template>
