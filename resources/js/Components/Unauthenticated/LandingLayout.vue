<script setup>
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

defineProps({
  companyName: String,
});
</script>

<template>
  <div class="grid h-full grid-cols-1 md:grid-cols-12">
    <div class="col-span-5 hidden h-full flex-col text-gray-200 md:flex">
      <div class="flex h-full w-full flex-col place-content-center gap-24 px-6">
        <div class="flex flex-col gap-4">
          <div>
            <img
              class="h-10 w-auto"
              src="https://tailwindui.com/img/logos/mark.svg?color=gray&shade=200"
              alt="Your Company"
            />
          </div>
          <p class="text-2xl font-light">Company Point-of-Sales</p>
          <p class="text-4xl font-black">{{ companyName ?? 'The Organisation' }}</p>
        </div>
      </div>

      <div class="flex flex-row items-center gap-4 px-4 pb-4 text-lg">
        <ExclamationTriangleIcon class="w-h-20 h-20" />
        <small
          >Unauthorised use is strictly prohibited. If you are not authorised, please exit from the System immediately.
          The use of this system is subject to the Computer Misuse Act.</small
        >
      </div>
    </div>
    <div class="col-span-1 h-full overflow-y-scroll bg-gray-200 md:col-span-7 md:rounded-lg">
      <slot />
    </div>
  </div>
</template>
