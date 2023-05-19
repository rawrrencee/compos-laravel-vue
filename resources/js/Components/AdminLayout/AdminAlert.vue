<script setup>
import { TransitionRoot } from '@headlessui/vue';
import { XCircleIcon } from '@heroicons/vue/24/outline';
import { CheckCircleIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <TransitionRoot
    appear
    :show="!!$page.props.flash.show"
    as="div"
    class="z-0"
    enter="transition duration-300"
    enter-from="opacity-0 -translate-y-full"
    enter-to="opacity-100 translate-y-0"
    leave="transition duration-300"
    leave-from="opacity-100 translate-y-0"
    leave-to="opacity-0 -translate-y-full"
  >
    <div class="flex rounded-md p-4" :class="$page.props.flash.status === 'error' ? 'bg-red-50' : 'bg-green-50'">
      <div class="flex-shrink-0">
        <CheckCircleIcon
          class="h-5 w-5 text-green-400"
          aria-hidden="true"
          v-if="$page.props.flash.status === 'success'"
        />
        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" v-if="$page.props.flash.status === 'error'" />
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-bold" :class="$page.props.flash.status === 'error' ? 'text-red-800' : 'text-green-800'">
          {{ $page.props.flash.message }}
        </h3>
        <p class="mt-1 text-sm text-gray-500" v-if="$page.props.flash.description">
          {{ $page.props.flash.description }}
        </p>
        <div class="mt-4">
          <div class="-mx-2 -my-1.5 flex gap-4">
            <Link
              v-if="$page.props.flash.route && $page.props.flash.id"
              as="button"
              :class="[
                $page.props.flash.status === 'error'
                  ? 'bg-red-50 text-red-800 hover:bg-red-100 focus:ring-red-600 focus:ring-offset-red-50'
                  : 'bg-green-50 text-green-800 hover:bg-green-100 focus:ring-green-600 focus:ring-offset-green-50',
                'rounded-md px-2 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50',
              ]"
              :href="
                route($page.props.flash.route, {
                  id: $page.props.flash.id,
                })
              "
            >
              View
            </Link>
            <button
              type="button"
              :class="[
                $page.props.flash.status === 'error'
                  ? 'bg-red-50 text-red-800 hover:bg-red-100 focus:ring-red-600 focus:ring-offset-red-50'
                  : 'bg-green-50 text-green-800 hover:bg-green-100 focus:ring-green-600 focus:ring-offset-green-50',
                'rounded-md px-2 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50',
              ]"
              @click="$page.props.flash.show = null"
            >
              Dismiss
            </button>
          </div>
        </div>
      </div>
    </div>
  </TransitionRoot>
</template>
