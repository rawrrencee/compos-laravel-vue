<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';
import { ChevronDownIcon, ChevronUpIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import AdminAlert from '../../AdminLayout/AdminAlert.vue';

defineProps({
  show: Boolean,
  selectedItems: Array,
  editForms: Array,
  editableHeaderKey: String,
  inputFields: Array,
  isLoading: Boolean,
  additionalData: Object,
});

defineEmits(['onDialogCloseClicked', 'onDialogSaveClicked']);
</script>

<template>
  <TransitionRoot as="template" :show="show">
    <Dialog as="div" class="relative z-40" @close="$emit('onDialogCloseClicked')">
      <div class="fixed inset-0" aria-hidden="true" />

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full">
            <TransitionChild
              as="template"
              enter="transform transition ease-in-out duration-300"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transform transition ease-in-out duration-300"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="pointer-events-auto w-screen max-w-2xl">
                <form
                  class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
                  @submit.prevent="$emit('onDialogSaveClicked')"
                >
                  <div class="h-0 flex-1 overflow-y-auto">
                    <div class="bg-primary px-4 py-6 sm:px-6">
                      <div class="flex items-center justify-between">
                        <DialogTitle class="text-base font-semibold leading-6 text-white"
                          >Bulk Edit ({{ selectedItems.length }})</DialogTitle
                        >
                        <div class="ml-3 flex h-7 items-center">
                          <button
                            type="button"
                            class="rounded-md bg-primary text-secondary hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                            @click="$emit('onDialogCloseClicked')"
                          >
                            <span class="sr-only">Close panel</span>
                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                          </button>
                        </div>
                      </div>
                      <div class="mt-1">
                        <p class="text-sm text-secondary">
                          Note: Only some fields are made available for bulk editing.
                        </p>
                      </div>
                    </div>
                    <AdminAlert v-if="$page.props.flash.type === 'dialog'" :flash="$page.props.flash" />
                    <div class="flex flex-1 flex-col justify-between py-6">
                      <slot name="bulk-fields" />
                      <Disclosure
                        as="div"
                        v-for="form in editForms"
                        :key="form.id"
                        v-slot="{ open }"
                        :default-open="true"
                      >
                        <DisclosureButton
                          class="sticky top-0 bg-gray-500 text-white p-4 font-semibold w-full flex gap-2 items-center justify-between border-t-2 border-t-gray-400"
                        >
                          <span class="text-left">Editing ID {{ form.id }}: {{ form[editableHeaderKey] }}</span>
                          <ChevronUpIcon v-if="open" class="h-4 w-4" />
                          <ChevronDownIcon v-else class="h-4 w-4" />
                        </DisclosureButton>
                        <transition
                          enter-active-class="transition duration-150"
                          enter-from-class="opacity-0 -translate-y-2"
                          enter-to-class="opacity-100 translate-y-0"
                          leave-active-class="transition duration-150"
                          leave-from-class="opacity-100 translate-y-0"
                          leave-to-class="opacity-0 -translate-y-2"
                        >
                          <DisclosurePanel class="p-4 grid grid-cols-1 gap-y-4">
                            <template v-for="input in inputFields" :key="input.key">
                              <div v-if="input.key === 'active'">
                                <label for="active" class="block text-sm font-medium leading-6 text-gray-900"
                                  >Active</label
                                >
                                <div class="mt-2">
                                  <input
                                    type="checkbox"
                                    name="active"
                                    class="toggle toggle-primary toggle-sm"
                                    v-model="form.active"
                                  />
                                </div>
                              </div>
                              <div v-else-if="input.key === 'company_id'">
                                <label for="company_id" class="block text-sm font-medium leading-6 text-gray-900"
                                  >Company</label
                                >
                                <div class="mt-2 flex flex-col gap-1">
                                  <select
                                    class="select select-bordered select-sm w-full"
                                    :class="form.errors[input.key] ? 'border-error' : ''"
                                    name="company_id"
                                    v-model="form.company_id"
                                  >
                                    <option disabled selected>Select a company</option>
                                    <option
                                      v-for="company in additionalData?.['companies'] ?? []"
                                      :key="company.id"
                                      :value="company.id"
                                    >
                                      {{ company.company_name }}
                                    </option>
                                  </select>
                                  <span v-if="form.errors[input.key]" class="text-error">
                                    {{ form.errors[input.key] }}
                                  </span>
                                </div>
                              </div>
                              <div v-else-if="input.key === 'include_tax'">
                                <label for="include_tax" class="block text-sm font-medium leading-6 text-gray-900"
                                  >Item Price Includes Tax</label
                                >
                                <div class="mt-2">
                                  <input
                                    type="checkbox"
                                    name="include_tax"
                                    class="toggle toggle-primary toggle-sm"
                                    v-model="form.include_tax"
                                  />
                                </div>
                              </div>
                              <div v-else>
                                <label :for="input.key" class="block text-sm font-medium leading-6 text-gray-900">{{
                                  input.title
                                }}</label>
                                <div class="mt-2">
                                  <input
                                    type="text"
                                    :id="input.key"
                                    :name="input.key"
                                    :class="[
                                      'input input-bordered input-sm w-full',
                                      form.errors[input.key] ? 'border-error' : '',
                                      input.key.includes('_code') && 'uppercase',
                                    ]"
                                    v-model="form[input.key]"
                                    @input="() => form.clearErrors([input.key])"
                                  />
                                </div>
                              </div>
                            </template>
                          </DisclosurePanel>
                        </transition>
                      </Disclosure>
                    </div>
                  </div>
                  <div class="grid grid-cols-2 sm:flex sm:flex-shrink-0 gap-2 justify-end px-4 py-4">
                    <button type="button" class="btn sm:grow sm:max-w-[10rem]" @click="$emit('onDialogCloseClicked')">
                      Cancel
                    </button>
                    <button
                      type="submit"
                      class="btn btn-primary sm:grow sm:max-w-[10rem]"
                      :class="isLoading ? 'loading' : ''"
                    >
                      Save
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
