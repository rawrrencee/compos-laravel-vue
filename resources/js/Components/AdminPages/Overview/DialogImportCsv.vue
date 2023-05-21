<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ArrowDownCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';
import AdminAlert from '../../AdminLayout/AdminAlert.vue';

const props = defineProps({
  context: String,
  show: Boolean,
  exportUrl: String,
  importForm: Object,
  isLoading: Boolean,
});

defineEmits(['onDialogCloseClicked', 'onDialogSaveClicked', 'onImportFileAdded']);

const onDownloadFileClicked = (url, data) => {
  axios({
    url,
    method: 'POST',
    responseType: 'blob',
    data,
  }).then((response) => {
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', !!props.context ? `${props.context}_template.csv` : 'template.csv');
    document.body.appendChild(link);
    link.click();
    link.remove();
  });
};
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
                        <DialogTitle class="text-base font-semibold leading-6 text-white">Import CSV</DialogTitle>
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
                          Download the CSV template, fill it up and upload the file to import new data.
                        </p>
                      </div>
                    </div>
                    <AdminAlert v-if="$page.props.flash.type === 'dialog'" :flash="$page.props.flash" />
                    <div class="flex flex-1 flex-col justify-between">
                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div class="flex flex-col text-sm">
                            <span class="font-medium leading-6 text-gray-900">Note</span>
                            <span class="mt-2">Please leave blanks for data that is empty.</span>
                            <span>Use 1 or 0 for true or false.</span>
                            <span>Do not use NULL or NA to represent empty values.</span>
                          </div>
                          <div>
                            <label for="project-name" class="block text-sm font-medium leading-6 text-gray-900"
                              >Template</label
                            >
                            <div class="mt-2">
                              <button
                                type="button"
                                class="btn btn-sm flex gap-2"
                                @click="onDownloadFileClicked(exportUrl)"
                              >
                                <ArrowDownCircleIcon class="h-4 w-4" />
                                <span>Download (.csv)</span>
                              </button>
                            </div>
                          </div>

                          <div class="flex flex-col gap-2">
                            <label for="import_csv" class="block text-sm font-medium leading-6 text-gray-900"
                              >File (.csv)</label
                            >
                            <input
                              type="file"
                              name="import_csv"
                              accept=".csv, .txt"
                              class="file-input file-input-sm file-input-bordered w-full max-w-xs"
                              :class="importForm.errors.import_file ? 'file-input-error' : ''"
                              @change="(event) => $emit('onImportFileAdded', event)"
                            />
                            <span class="text-error" v-if="importForm.errors.import_file">{{
                              importForm.errors.import_file
                            }}</span>
                          </div>
                        </div>
                      </div>
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
                      Import
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
