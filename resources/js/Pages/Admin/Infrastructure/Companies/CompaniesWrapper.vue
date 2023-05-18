<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const secondaryNavigation = [
    {
        name: "Overview",
        href: "admin/infrastructure/companies",
        children: [
            {
                name: "Add New Company",
                href: "admin/infrastructure/companies/add",
            },
            {
                name: "Edit Company",
                href: "admin/infrastructure/companies/edit",
            },
        ],
    },
];
</script>

<template>
    <AdminLayout :secondary-navigation="secondaryNavigation">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                Companies
            </h2>
        </template>

        <div class="h-full sm:px-6 lg:px-8">
            <slot />
        </div>
    </AdminLayout>
</template>
