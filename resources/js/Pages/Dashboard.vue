<script setup>
import Welcome from '@/Components/Welcome.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Dashboard" />
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
          <Welcome />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
