<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import {
  ArrowDownOnSquareIcon,
  ArrowUpOnSquareIcon,
  EllipsisVerticalIcon,
  PencilIcon,
  PlusCircleIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  selectedItems: Array,
  addNewUrl: String,
  showEditDeleteBtn: Boolean,
  isLoading: Boolean,
  showAdditionalMenu: {
    default: true,
    type: Boolean,
  },
});

defineEmits(['buttonClicked']);
</script>

<template>
  <div class="sticky bottom-0 z-40 px-4 py-4 sm:hidden">
    <div class="grid grid-cols-2 gap-2" v-if="showEditDeleteBtn">
      <button
        type="button"
        class="btn btn-primary btn-block"
        :class="isLoading ? 'loading' : ''"
        @click="$emit('buttonClicked', { action: 'edit' })"
      >
        <div class="flex items-center gap-2">
          <PencilIcon class="h-5 w-5" />
          <span>Edit ({{ selectedItems.length }})</span>
        </div>
      </button>
      <button
        type="button"
        class="btn btn-error btn-block"
        :class="isLoading ? 'loading' : ''"
        @click="$emit('buttonClicked', { action: 'delete' })"
      >
        <div class="flex items-center gap-2">
          <XMarkIcon class="h-5 w-5" />
          <span>Delete ({{ selectedItems.length }})</span>
        </div>
      </button>
    </div>
    <div class="join flex" v-else-if="addNewUrl">
      <Link
        as="button"
        :href="route(addNewUrl)"
        class="btn btn-primary join-item grow"
        :class="isLoading ? 'loading' : ''"
      >
        <div class="flex items-center gap-2">
          <PlusCircleIcon class="h-5 w-5" />
          <span>Add New</span>
        </div>
      </Link>

      <Menu v-if="showAdditionalMenu">
        <MenuButton class="btn btn-primary join-item !rounded-r-lg">
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
              class="absolute bottom-full right-0 mb-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
              <MenuItem v-slot="{ active }">
                <button
                  :class="[
                    active ? 'bg-primary text-white' : 'text-gray-900',
                    'group flex w-full items-center gap-2 rounded-t-md px-3 py-3 text-xs font-semibold uppercase',
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
                    'group flex w-full items-center gap-2 rounded-b-md px-3 py-3 text-xs font-semibold uppercase',
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
</template>
