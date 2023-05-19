<script setup>
import SidebarItems from './SidebarItems.vue';

defineProps({
  employeeNavigation: Array,
  adminNavigation: Array,
});
</script>

<template>
  <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-gray-50 px-6">
    <div class="flex h-16 shrink-0 items-center">
      <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
    </div>
    <nav class="flex flex-1 flex-col">
      <ul role="list" class="flex flex-1 flex-col gap-y-5">
        <span class="font-semibold text-gray-400 uppercase">Employee</span>
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <SidebarItems :navigation="employeeNavigation" />
          </ul>
        </li>
        <span class="font-semibold text-gray-400 uppercase">Admin</span>
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <SidebarItems :navigation="adminNavigation" />
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</template>
