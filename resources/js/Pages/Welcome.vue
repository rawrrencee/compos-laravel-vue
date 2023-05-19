<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />

  <main class="isolate min-h-full">
    <img
      src="https://images.unsplash.com/photo-1545972154-9bb223aac798?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=3050&q=80&exp=8&con=-15&sat=-75"
      alt=""
      class="absolute inset-0 -z-10 h-full w-full object-cover object-top"
    />

    <div v-if="canLogin" class="fixed top-0 right-0 p-6 text-right">
      <Link
        v-if="$page.props.auth.user"
        :href="route('dashboard')"
        class="font-semibold text-gray-100 hover:text-gray-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
        >Dashboard</Link
      >

      <template v-else>
        <Link
          :href="route('login')"
          class="font-semibold text-gray-100 hover:text-gray-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
          >Log in</Link
        >

        <Link
          v-if="canRegister"
          :href="route('register')"
          class="ml-4 font-semibold text-gray-100 hover:text-gray-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
          >Register</Link
        >
      </template>
    </div>

    <div class="mx-auto max-w-7xl px-6 py-32 text-center sm:py-40 lg:px-8">
      <p class="text-base font-semibold leading-8 text-white">Compos</p>
      <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl">Company Point-of-Sales</h1>
      <p class="mt-4 text-base text-white/70 sm:mt-6">Landing Page</p>
      <div class="mt-10 flex justify-center">
        <a href="#" class="text-sm font-semibold leading-7 text-white">Work in Progress</a>
      </div>
    </div>
  </main>
</template>

<style>
.bg-dots-darker {
  background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
  .dark\:bg-dots-lighter {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
  }
}
</style>
