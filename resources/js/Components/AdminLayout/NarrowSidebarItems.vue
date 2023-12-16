<script setup>
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { ArrowLeftCircleIcon } from '@heroicons/vue/24/outline';
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
    <template v-if="item.children?.length > 0">
      <Popover class="relative">
        <PopoverButton
          as="div"
          :class="[
            isCurrent(item, currentRoute) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:bg-gray-200',
            'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700 hover:cursor-pointer',
          ]"
        >
          <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
          <span class="sr-only">{{ item.name }}</span>
        </PopoverButton>

        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="transform opacity-0 -translate-x-4"
          enter-to-class="transform opacity-100 translate-x-0"
          leave-active-class="transition ease-in duration-75"
          leave-from-class="transform opacity-100 translate-x-0"
          leave-to-class="transform opacity-0 -translate-x-4"
        >
          <PopoverPanel
            class="absolute -mt-10 ml-20 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            v-slot="{ close }"
          >
            <li class="flex items-center justify-between gap-2 px-4 py-2 text-sm uppercase leading-6 text-gray-400">
              <ArrowLeftCircleIcon class="h-5 w-5 cursor-pointer hover:text-secondary" @click="close()" />{{
                item.name
              }}
            </li>
            <li v-for="subItem in item.children" :key="subItem.name">
              <Link
                :href="route(subItem.href)"
                :class="[
                  isCurrent(subItem, currentRoute) ? 'font-semibold' : '',
                  'block py-2 pl-4 text-sm leading-6 text-gray-700 hover:text-secondary',
                ]"
              >
                {{ subItem.name }}</Link
              >
            </li>
          </PopoverPanel>
        </transition>
      </Popover>
    </template>
    <template v-else>
      <Link
        :href="route(item.href)"
        :class="[
          isCurrent(item, currentRoute) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:bg-gray-200',
          'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700',
        ]"
      >
        <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
        <span class="sr-only">{{ item.name }}</span>
      </Link>
    </template>
  </li>
</template>
