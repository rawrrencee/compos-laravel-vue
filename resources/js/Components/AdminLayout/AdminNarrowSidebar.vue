<script setup>
import NarrowSidebarItems from './NarrowSidebarItems.vue';

defineProps({
  employeeNavigation: Array,
  adminNavigation: Array,
});
</script>

<template>
  <div
    class="hidden border-r border-gray-200 pr-8 lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:block lg:w-20 lg:bg-gray-50 lg:pl-6"
  >
    <div class="flex h-16 shrink-0 items-center">
      <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
    </div>
    <nav class="mt-5 flex flex-1 flex-col">
      <ul role="list" class="flex flex-1 flex-col gap-y-5">
        <div class="text-center">
          <span class="font-semibold uppercase text-gray-400">E</span>
        </div>
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <NarrowSidebarItems :navigation="employeeNavigation" />
          </ul>
        </li>
        <div class="text-center">
          <span class="font-semibold uppercase text-gray-400">A</span>
        </div>
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <NarrowSidebarItems :navigation="adminNavigation" />
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</template>
