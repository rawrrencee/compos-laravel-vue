<script setup>
import { TransitionRoot } from '@headlessui/vue';
import { XCircleIcon } from '@heroicons/vue/24/outline';
import { CheckCircleIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

defineProps({
  flash: Object,
});

defineEmits(['buttonClicked']);
</script>

<template>
  <TransitionRoot
    v-if="flash"
    appear
    :show="!!flash.show"
    as="div"
    class="z-0"
    enter="transition duration-300"
    enter-from="opacity-0 -translate-y-2"
    enter-to="opacity-100 translate-y-0"
    leave="transition duration-300"
    leave-from="opacity-100 translate-y-0"
    leave-to="opacity-0 -translate-y-2"
  >
    <div class="flex rounded-md p-4" :class="flash.status === 'error' ? 'bg-red-50' : 'bg-green-50'">
      <div class="flex-shrink-0">
        <CheckCircleIcon class="h-5 w-5 text-green-400" aria-hidden="true" v-if="flash.status === 'success'" />
        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" v-if="flash.status === 'error'" />
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-bold" :class="flash.status === 'error' ? 'text-red-800' : 'text-green-800'">
          {{ flash.message }}
        </h3>
        <p
          v-if="flash.description"
          class="mt-1 text-sm"
          :class="flash.status === 'error' ? 'text-red-800' : 'text-green-800'"
        >
          {{ flash.description }}
        </p>
        <div class="mt-4">
          <div class="-mx-2 -my-1.5 flex gap-4">
            <Link
              v-if="flash.route && flash.id"
              as="button"
              :class="[
                flash.status === 'error'
                  ? 'bg-red-50 text-red-800 hover:bg-red-100 focus:ring-red-600 focus:ring-offset-red-50'
                  : 'bg-green-50 text-green-800 hover:bg-green-100 focus:ring-green-600 focus:ring-offset-green-50',
                'rounded-md px-2 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50',
              ]"
              :href="
                route(flash.route, {
                  id: flash.id,
                })
              "
            >
              View
            </Link>
            <button
              type="button"
              :class="[
                flash.status === 'error'
                  ? 'bg-red-50 text-red-800 hover:bg-red-100 focus:ring-red-600 focus:ring-offset-red-50'
                  : 'bg-green-50 text-green-800 hover:bg-green-100 focus:ring-green-600 focus:ring-offset-green-50',
                'rounded-md px-2 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50',
              ]"
              @click="
                () => {
                  flash.show = null;
                  $emit('buttonClicked');
                }
              "
            >
              Dismiss
            </button>
          </div>
        </div>
      </div>
    </div>
  </TransitionRoot>
</template>
