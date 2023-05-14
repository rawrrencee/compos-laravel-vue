<script setup></script>

<template>
    <div class="pb-4">
        <div
            class="grow ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg overflow-x-scroll"
        >
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <slot name="thead" />
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <slot name="tbody" />
                </tbody>
            </table>
        </div>
    </div>
</template>
