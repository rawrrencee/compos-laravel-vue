<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const secondaryNavigation = [
    {
        name: "Overview",
        href: "admin/users/employees",
        isCurrent: true,
    },
    {
        name: "Salaries",
        href: "admin/users/employees/salaries",
        isCurrent: false,
    },
    {
        name: "Timecards",
        href: "admin/users/employees/timecards",
        isCurrent: false,
    },
    {
        name: "Designations",
        href: "admin/users/employees/designations",
        isCurrent: false,
    },
    {
        name: "Permissions",
        href: "admin/users/employees/permissions",
        isCurrent: false,
    },
    {
        name: "Requests",
        href: "admin/users/employees/requests",
        isCurrent: false,
    },
];
</script>

<template>
    <AdminLayout title="Dashboard" :secondary-navigation="secondaryNavigation">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Employees
            </h2>
        </template>

        <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <slot />
        </div>
    </AdminLayout>
</template>
