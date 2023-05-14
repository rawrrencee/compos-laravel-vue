<script setup></script>

<template>
    <div class="pb-4 grow">
        <div
            class="ring-1 ring-gray-200 sm:mx-0 sm:rounded-lg overflow-x-scroll"
        >
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <slot name="thead" />
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <slot name="tbody" />
                </tbody>
            </table>
        </div>
    </div>
</template>
