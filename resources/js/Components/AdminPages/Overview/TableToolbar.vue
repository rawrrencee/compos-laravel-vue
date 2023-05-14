<script setup>
import {
    BarsArrowDownIcon,
    BarsArrowUpIcon,
    FunnelIcon,
    PencilIcon,
    PlusCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    selectedItems: Array,
    addNewUrl: String,
});

defineEmits(["buttonClicked"]);

const showEditDeleteBtn = computed(() => props.selectedItems.length > 0);
</script>

<template>
    <div class="flex justify-center items-end sm:justify-between py-4">
        <div class="hidden sm:flex gap-2" v-if="showEditDeleteBtn">
            <button
                type="button"
                class="btn btn-primary"
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
                @click="$emit('buttonClicked', { action: 'delete' })"
            >
                <div class="flex gap-2 items-center">
                    <XMarkIcon class="w-5 h-5" />
                    <span>Delete ({{ selectedItems?.length }})</span>
                </div>
            </button>
        </div>
        <div
            class="flex gap-2 font-medium"
            :class="showEditDeleteBtn && 'sm:hidden'"
        >
            <button
                type="button"
                class="px-1 flex gap-2 items-center text-gray-400 hover:text-gray-700"
                @click="$emit('buttonClicked', { action: 'filter' })"
            >
                <FunnelIcon class="h-5 w-5" />
                <span>Filter</span>
            </button>
            <button
                type="button"
                class="px-1 flex gap-2 items-center"
                @click="$emit('buttonClicked', { action: 'sort' })"
            >
                <BarsArrowUpIcon
                    class="h-5 w-5 text-gray-400 hover:text-gray-700"
                    v-if="false"
                />
                <BarsArrowDownIcon
                    class="h-5 w-5 text-gray-400 hover:text-gray-700"
                />
                <span class="text-gray-400 hover:text-gray-700">Sort By</span>
            </button>
        </div>
        <Link as="button" :href="route(addNewUrl)" class="hidden sm:btn">
            <div class="flex gap-2 items-center">
                <PlusCircleIcon class="w-5 h-5" />
                <span>Add New</span>
            </div>
        </Link>
    </div>
</template>
