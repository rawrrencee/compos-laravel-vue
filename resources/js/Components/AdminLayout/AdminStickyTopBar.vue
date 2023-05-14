<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Bars3CenterLeftIcon, Bars3Icon } from "@heroicons/vue/20/solid";
import { Link, router } from "@inertiajs/vue3";

defineProps({
    isMobileSidebarOpen: Boolean,
    isDesktopNarrowSidebarShown: Boolean,
});

defineEmits([
    "update:isMobileSidebarOpen",
    "update:isDesktopNarrowSidebarShown",
]);
</script>

<template>
    <div
        class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-6 border-b border-white/5 bg-gray-50 px-4 shadow-sm sm:px-6 lg:px-8"
    >
        <button
            type="button"
            class="-m-2.5 p-2.5 text-gray-500 lg:hidden"
            @click="$emit('update:isMobileSidebarOpen', true)"
        >
            <span class="sr-only">Open sidebar</span>
            <Bars3Icon class="h-5 w-5" aria-hidden="true" />
        </button>

        <button
            type="button"
            class="-m-2.5 p-2.5 text-gray-500 hidden lg:block"
            @click="
                $emit(
                    'update:isDesktopNarrowSidebarShown',
                    !isDesktopNarrowSidebarShown
                )
            "
        >
            <span class="sr-only">Use narrow sidebar</span>
            <Bars3Icon
                v-if="isDesktopNarrowSidebarShown"
                class="h-5 w-5"
                aria-hidden="true"
            />
            <Bars3CenterLeftIcon v-else class="h-5 w-5" aria-hidden="true" />
        </button>

        <div class="flex flex-1 gap-x-4 self-center justify-end lg:gap-x-6">
            <Menu as="div" class="ml-3">
                <div>
                    <MenuButton
                        class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                    >
                        <span class="sr-only">Open user menu</span>
                        <img
                            class="h-8 w-8 rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt=""
                        />
                    </MenuButton>
                </div>
                <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <MenuItems
                        class="absolute right-0 z-10 mt-2 mr-4 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    >
                        <MenuItem v-slot="{ active }">
                            <Link
                                :href="route('profile.show')"
                                :class="[
                                    active ? 'bg-gray-100' : '',
                                    'block px-4 py-2 text-sm text-gray-700',
                                ]"
                                >Profile</Link
                            >
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <button
                                @click="() => router.post(route('logout'))"
                                :class="[
                                    active ? 'bg-gray-100' : '',
                                    'block w-full px-4 py-2 text-left text-sm text-gray-700',
                                ]"
                            >
                                Sign out
                            </button>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>
</template>
