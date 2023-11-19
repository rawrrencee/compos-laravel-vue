<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, TransitionRoot } from '@headlessui/vue';
import { ChevronRightIcon } from '@heroicons/vue/20/solid';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { isCurrent } from '../../Util/Layout';

defineProps({
  navigation: Array,
});

const currentRoute = ref(route().current());
</script>

<template>
  <li v-for="item in navigation" :key="item.name">
    <Link
      v-if="!item.children"
      :href="route(item.href)"
      :class="[
        isCurrent(item, currentRoute) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:bg-gray-200',
        'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700',
      ]"
    >
      <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
      {{ item.name }}
    </Link>

    <Disclosure as="div" v-else v-slot="{ open }" :default-open="isCurrent(item, currentRoute)">
      <div
        :class="[
          isCurrent(item, currentRoute) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:bg-gray-200',
          'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700',
        ]"
      >
        <Link :href="route(item.href)" class="flex gap-x-3">
          <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
          {{ item.name }}</Link
        >
        <DisclosureButton as="div" class="flex-grow cursor-pointer">
          <ChevronRightIcon
            :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto h-5 w-5 shrink-0']"
            aria-hidden="true"
          />
        </DisclosureButton>
      </div>
      <TransitionRoot
        :show="open"
        enter="transition ease-in-out duration-100 transform"
        enter-from="-translate-y-5 opacity-0"
        enter-to="translate-y-0 opacity-100"
        leave="transition ease-in-out duration-100 transform"
        leave-from="translate-y-0 opacity-100"
        leave-to="-translate-y-5 opacity-0"
      >
        <DisclosurePanel as="ul" class="mt-1 px-2">
          <li v-for="subItem in item.children" :key="subItem.name">
            <Link
              :href="route(subItem.href)"
              :class="[
                isCurrent(subItem, currentRoute) ? 'font-semibold' : '',
                'block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 hover:text-secondary',
              ]"
            >
              {{ subItem.name }}</Link
            >
          </li>
        </DisclosurePanel>
      </TransitionRoot>
    </Disclosure>
  </li>
</template>
