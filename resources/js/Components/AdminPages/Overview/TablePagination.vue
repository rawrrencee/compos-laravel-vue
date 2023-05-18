<script setup>
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import {
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import { computed, ref } from "vue";

const props = defineProps({
    paginatedResults: Object,
});

defineEmits(["onGoToPageClicked"]);

const goToPage = ref(props.paginatedResults?.current_page ?? 1);
const perPage = ref(props.paginatedResults?.per_page ?? 10);
const pagesArray = computed(() => {
    let start = Math.max(props.paginatedResults?.current_page - 1, 1);
    let end = Math.min(start + 2, props.paginatedResults?.last_page);

    if (
        props.paginatedResults?.current_page >
        props.paginatedResults?.last_page - 1
    ) {
        start = Math.max(props.paginatedResults?.last_page - 2, 1);
    }

    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});
</script>

<template>
    <div
        class="py-4 flex flex-col items-center gap-4 justify-center sm:flex-row sm:justify-between"
    >
        <Popover v-slot="{ close }" class="relative">
            <PopoverButton class="btn btn-sm btn-outline">
                Showing {{ paginatedResults?.from }} -
                {{ paginatedResults?.to }} of
                {{ paginatedResults?.total }} results
            </PopoverButton>

            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="translate-y-1 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="translate-y-1 opacity-0"
            >
                <PopoverPanel
                    class="absolute z-40 left-1/2 -translate-x-1/2 sm:left-0 sm:translate-x-0 mt-3 w-96 bottom-14 transform px-4 sm:px-0 lg:max-w-3xl"
                >
                    <div
                        class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                    >
                        <div
                            class="relative grid gap-4 bg-white p-4 items-center"
                        >
                            <h3 class="font-bold">Pagination Options</h3>
                            <div class="flex flex-col gap-2">
                                <label
                                    for="go_to_page"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Go to page</label
                                >
                                <input
                                    type="number"
                                    name="go_to_page"
                                    class="input input-sm input-bordered w-full [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    v-model="goToPage"
                                />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label
                                    for="per_page"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Items per page</label
                                >
                                <select
                                    class="select select-sm select-bordered w-full"
                                    v-model="perPage"
                                >
                                    <template
                                        v-for="selectOption in [
                                            '10',
                                            '20',
                                            '50',
                                            '100',
                                            '200',
                                            '500',
                                            '1000',
                                            '2000',
                                            '5000',
                                        ]"
                                        :key="selectOption"
                                    >
                                        <option
                                            :disabled="
                                                selectOption ===
                                                paginatedResults?.per_page
                                            "
                                            :selected="
                                                selectOption ===
                                                paginatedResults?.per_page
                                            "
                                        >
                                            {{ selectOption }}
                                        </option>
                                    </template>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 my-2">
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    @click="close"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    @click="
                                        $emit('onGoToPageClicked', {
                                            page: 1,
                                            perPage,
                                        })
                                    "
                                >
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </PopoverPanel>
            </transition>
        </Popover>
        <div class="flex items-center justify-center sm:justify-end gap-2">
            <button
                :disabled="paginatedResults?.current_page === 1"
                @click="$emit('onGoToPageClicked', { page: 1, perPage })"
                class="btn btn-sm btn-ghost disabled:bg-gray-50"
            >
                <ChevronDoubleLeftIcon class="h-3 w-3" />
            </button>
            <button
                :disabled="paginatedResults?.current_page === 1"
                @click="
                    $emit('onGoToPageClicked', {
                        page: paginatedResults?.current_page - 1,
                        perPage,
                    })
                "
                class="btn btn-sm btn-ghost disabled:bg-gray-50"
            >
                <ChevronLeftIcon class="h-3 w-3" />
            </button>
            <div class="flex gap-2">
                <template v-for="page in pagesArray" :key="page">
                    <button
                        :class="[
                            page === paginatedResults?.current_page
                                ? ''
                                : 'btn-ghost',
                            'btn btn-sm',
                        ]"
                        @click="$emit('onGoToPageClicked', { page, perPage })"
                    >
                        {{ page }}
                    </button>
                </template>
            </div>
            <button
                :disabled="
                    paginatedResults?.current_page ===
                    paginatedResults?.last_page
                "
                @click="
                    () =>
                        $emit('onGoToPageClicked', {
                            page: paginatedResults?.current_page + 1,
                            perPage,
                        })
                "
                class="btn btn-sm btn-ghost disabled:bg-gray-50"
            >
                <ChevronRightIcon class="h-3 w-3" />
            </button>
            <button
                :disabled="
                    paginatedResults?.current_page ===
                    paginatedResults?.last_page
                "
                @click="
                    $emit('onGoToPageClicked', {
                        page: paginatedResults?.last_page,
                        perPage,
                    })
                "
                class="btn btn-sm btn-ghost disabled:bg-gray-50"
            >
                <ChevronDoubleRightIcon class="h-3 w-3" />
            </button>
        </div>
    </div>
</template>
