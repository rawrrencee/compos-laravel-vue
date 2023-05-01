<script setup>
import {
Bars3CenterLeftIcon,
Bars3Icon,
MagnifyingGlassIcon,
} from "@heroicons/vue/20/solid";

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

        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
            <form class="flex flex-1" action="#" method="GET">
                <label for="search-field" class="sr-only">Search</label>
                <div class="relative w-full">
                    <MagnifyingGlassIcon
                        class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-500"
                        aria-hidden="true"
                    />
                    <input
                        id="search-field"
                        class="block h-full w-full border-0 bg-transparent py-0 pl-8 pr-0 text-gray-500 focus:ring-0 sm:text-sm"
                        placeholder="Search..."
                        type="search"
                        name="search"
                    />
                </div>
            </form>
        </div>
    </div>
</template>
