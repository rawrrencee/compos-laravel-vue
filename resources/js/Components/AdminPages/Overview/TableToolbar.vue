<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import {
  ArrowDownOnSquareIcon,
  ArrowUpOnSquareIcon,
  EllipsisVerticalIcon,
  FunnelIcon,
  PencilIcon,
  PlusCircleIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  selectedItems: Array,
  addNewUrl: String,
  showEditDeleteBtn: Boolean,
  showFilters: Boolean,
  appliedFilterCount: Number,
  isLoading: Boolean,
});

defineEmits(['buttonClicked']);
</script>

<template>
  <div class="flex justify-center items-end sm:justify-between py-4">
    <div class="hidden sm:flex gap-2" v-if="showEditDeleteBtn">
      <button
        type="button"
        class="btn btn-primary"
        :class="isLoading ? 'loading' : ''"
        @click="$emit('buttonClicked', { action: 'edit' })"
      >
        <div class="flex gap-2 items-center">
          <PencilIcon class="w-5 h-5" />
          <span>Edit ({{ selectedItems?.length }})</span>
        </div>
      </button>
      <button
        type="button"
        class="btn btn-error"
        :class="isLoading ? 'loading' : ''"
        @click="$emit('buttonClicked', { action: 'delete' })"
      >
        <div class="flex gap-2 items-center">
          <XMarkIcon class="w-5 h-5" />
          <span>Delete ({{ selectedItems?.length }})</span>
        </div>
      </button>
    </div>
    <div class="flex gap-2 font-medium" :class="showEditDeleteBtn && 'sm:hidden'">
      <button
        type="button"
        class="px-1 flex gap-2 items-center text-gray-400 hover:text-gray-700"
        @click="$emit('buttonClicked', { action: 'filter' })"
      >
        <FunnelIcon class="h-5 w-5" v-if="!showFilters" />
        <XMarkIcon class="h-5 w-5 text-gray-900" v-else />
        <span :class="showFilters ? 'text-gray-900' : ''"
          >Filter{{ appliedFilterCount > 0 ? `s (${appliedFilterCount})` : '' }}</span
        >
      </button>
    </div>
    <div class="hidden sm:join">
      <Link as="button" :href="route(addNewUrl)" class="btn join-item" :class="isLoading ? 'loading' : ''">
        <div class="flex gap-2 items-center">
          <PlusCircleIcon class="w-5 h-5" />
          <span>Add New</span>
        </div>
      </Link>
      <Menu>
        <MenuButton class="btn join-item !rounded-r-lg">
          <EllipsisVerticalIcon class="h-4 w-4" />
        </MenuButton>

        <div class="relative">
          <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <MenuItems
              class="absolute right-0 top-full mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
            >
              <MenuItem v-slot="{ active }">
                <button
                  type="button"
                  :class="[
                    active ? 'bg-primary text-white' : 'text-gray-900',
                    'flex w-full gap-2 items-center rounded-t-md px-3 py-3 text-xs font-semibold uppercase',
                  ]"
                  @click="$emit('buttonClicked', { action: 'import' })"
                >
                  <ArrowUpOnSquareIcon class="h-5 w-5" />
                  <span>Import (.csv)</span>
                </button>
              </MenuItem>
              <MenuItem v-slot="{ active }">
                <button
                  type="button"
                  :class="[
                    active ? 'bg-primary text-white' : 'text-gray-900',
                    'flex w-full gap-2 items-center rounded-b-md px-3 py-3 text-xs font-semibold uppercase',
                  ]"
                  @click="$emit('buttonClicked', { action: 'export' })"
                >
                  <ArrowDownOnSquareIcon class="h-5 w-5" />
                  <span>Export all (.csv)</span>
                </button>
              </MenuItem>
            </MenuItems>
          </transition>
        </div>
      </Menu>
    </div>
  </div>
  <slot name="filter" />
</template>
