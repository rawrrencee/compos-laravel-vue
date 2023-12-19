<script setup>
import LandingLayout from '@/Components/Unauthenticated/LandingLayout.vue';
import { ArrowLeftIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import { Head, router } from '@inertiajs/vue3';

defineProps({
  companyName: String,
});
</script>

<template>
  <main class="relative h-full bg-gray-800">
    <Head title="Registration Successful" />
    <LandingLayout :company-name="companyName">
      <div class="flex h-full flex-col place-content-center items-center gap-12 px-12 py-6">
        <CheckCircleIcon class="h-28 w-28 text-success" />
        <div class="flex flex-col gap-6 text-center">
          <h2 class="text-xl font-semibold leading-tight text-gray-900">Registration Successful</h2>
          <span>{{ $page.props.flash?.message }}</span>
        </div>
        <button type="button" class="btn btn-link grow-0" @click="router.get(route('unauth.employees.register.view'))">
          <ArrowLeftIcon class="h-3 w-3 text-primary" /><span>Back</span>
        </button>
      </div>
    </LandingLayout>
  </main>
</template>
