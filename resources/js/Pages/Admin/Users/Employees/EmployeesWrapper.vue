<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const secondaryNavigation = [
  {
    name: 'Overview',
    href: 'admin/users/employees',
    children: [
      {
        name: 'Add New Employee',
        href: 'admin/users/employees/add',
      },
    ],
  },
  {
    name: 'Salaries',
    href: 'admin/users/employees/salaries',
  },
  {
    name: 'Timecards',
    href: 'admin/users/employees/timecards',
  },
  {
    name: 'Designations',
    href: 'admin/users/employees/designations',
  },
  {
    name: 'Permissions',
    href: 'admin/users/employees/permissions',
  },
  {
    name: 'Requests',
    href: 'admin/users/employees/requests',
  },
];
</script>

<template>
  <AdminLayout :secondary-navigation="secondaryNavigation">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-900 leading-tight">Employees</h2>
    </template>

    <div class="h-full sm:px-6 lg:px-8">
      <slot />
    </div>
  </AdminLayout>
</template>
