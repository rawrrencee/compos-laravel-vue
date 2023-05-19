<script setup></script>

<template>
  <div class="grow pb-4">
    <div class="ring-1 ring-gray-200 sm:mx-0 sm:rounded-lg overflow-x-scroll">
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
