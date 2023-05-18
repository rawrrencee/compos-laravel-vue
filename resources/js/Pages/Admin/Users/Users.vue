<script setup>
import AdminLayout from "../../../Layouts/AdminLayout.vue";

const secondaryNavigation = [
    {
        name: "Overview",
        href: "admin/users",
        isCurrent: true,
    },
];
</script>

<template>
    <AdminLayout :secondary-navigation="secondaryNavigation">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                ></div>
            </div>
        </div>
    </AdminLayout>
</template>
