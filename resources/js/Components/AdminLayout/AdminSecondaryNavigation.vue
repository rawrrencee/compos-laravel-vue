<script setup>
import { isCurrent } from "@/Util/Layout";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";

import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { ChevronRightIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";

const props = defineProps({
    secondaryNavigation: Array,
    currentRoute: String,
});

const currentRouteName = computed(() => {
    return props?.secondaryNavigation?.find(
        (n) => n.href === props.currentRoute
    )?.name;
});
const breadcrumbParent = computed(() => {
    return props?.secondaryNavigation?.find((n) =>
        n.children?.some((c) => c.href === props.currentRoute)
    );
});
const breadcrumbChild = computed(() => {
    return props?.secondaryNavigation
        ?.find((n) => n.children?.some((c) => c.href === props.currentRoute))
        ?.children?.find((c) => c.href === props.currentRoute);
});
</script>

<template>
    <nav>
        <div
            class="flex min-w-full flex-none gap-x-6 text-sm font-semibold text-gray-400 px-4 pb-4 sm:px-6 lg:px-8"
            v-if="breadcrumbChild"
        >
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex items-center">
                            <Link
                                :href="route(breadcrumbParent.href)"
                                class="text-secondary hover:text-gray-700"
                                :aria-current="breadcrumbParent"
                                >{{ breadcrumbParent.name }}
                            </Link>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <ChevronRightIcon
                                class="h-5 w-5 flex-shrink-0 text-secondary"
                                aria-hidden="true"
                            />
                            <span class="ml-4 text-primary">{{
                                breadcrumbChild.name
                            }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <template v-else>
            <Menu as="div" class="px-4 pb-4 sm:px-6 lg:px-8 md:hidden">
                <div>
                    <MenuButton
                        class="inline-flex w-full justify-between items-center py-2 rounded-md bg-gray-100 text-gray-600 font-medium hover:bg-gray-200"
                    >
                        <span class="pl-4">{{ currentRouteName }}</span>
                        <span class="pointer-events-none pr-2">
                            <ChevronDownIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </span>
                    </MenuButton>
                </div>

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
                            class="absolute z-10 w-full mt-2 origin-top-right divide-y divide-gray-100 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        >
                            <MenuItem
                                v-for="item in secondaryNavigation"
                                :key="item.name"
                                v-slot="{ active }"
                            >
                                <Link
                                    :href="route(item.href)"
                                    :class="[
                                        active ? 'bg-gray-100' : '',
                                        'block px-4 py-2 text-sm text-gray-700',
                                    ]"
                                    >{{ item.name }}</Link
                                >
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </div>
            </Menu>
            <div
                class="hidden md:flex overflow-x-auto border-b border-white/10 pb-4"
            >
                <ul
                    role="list"
                    class="flex min-w-full flex-none gap-x-6 text-sm font-semibold text-gray-400 px-4 sm:px-6 lg:px-8"
                >
                    <li v-for="item in secondaryNavigation" :key="item.name">
                        <Link
                            :href="route(item.href)"
                            :class="[
                                isCurrent(item, currentRoute, true)
                                    ? 'text-primary'
                                    : '',
                                'hover:text-secondary',
                            ]"
                            >{{ item.name }}</Link
                        >
                    </li>
                </ul>
            </div>
        </template>
    </nav>
</template>
