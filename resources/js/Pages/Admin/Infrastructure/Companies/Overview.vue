<script setup>
import CompaniesWrapper from '@/Pages/Admin/Infrastructure/Companies/CompaniesWrapper.vue';
import { openInNewWindow } from '@/Util/Common';
import { getImgSrcFromPath } from '@/Util/Photo';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ArrowDownCircleIcon, EyeIcon, PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ColouredBadge from '../../../../Components/AdminPages/ColouredBadge.vue';
import Error404 from '../../../../Components/AdminPages/Error404.vue';
import NoResults from '../../../../Components/AdminPages/NoResults.vue';
import TableMain from '../../../../Components/AdminPages/Overview/TableMain.vue';
import TablePagination from '../../../../Components/AdminPages/Overview/TablePagination.vue';
import TableSortableHeader from '../../../../Components/AdminPages/Overview/TableSortableHeader.vue';
import TableStickyFooter from '../../../../Components/AdminPages/Overview/TableStickyFooter.vue';
import TableToolbar from '../../../../Components/AdminPages/Overview/TableToolbar.vue';

// #region Page Variables
const props = defineProps({
  paginatedResults: {
    type: Object,
    required: false,
    default: {
      data: [],
    },
  },
  sortBy: String,
  orderBy: String,
  tableFilterOptions: Object,
  viewCompany: Object | undefined,
});
const tableFilterOptions = useForm({
  company_name: props?.tableFilterOptions?.company_name ?? '',
  show_deleted: props?.tableFilterOptions?.show_deleted === null ? false : !!props?.tableFilterOptions?.show_deleted,
});
const moduleUrl = 'admin/infrastructure/companies';
const addNewUrl = `${moduleUrl}/add`;
const editUrl = `${moduleUrl}/edit`;
const exportUrl = `${route('admin/infrastructure/companies/export')}`;
const tableHeaderTitles = [
  { key: 'company_name', title: 'Company Name' },
  { key: 'active', title: 'Active' },
  { key: 'created_at', title: 'Created At' },
];
const viewCompanyLabels = [
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website_url', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
];
// #endregion Page Variables

// #region Ref variables
const isImportDialogOpen = ref(false);
const isViewDialogOpen = ref(false);
const selectedTableRows = ref([]);
const showFilters = ref(false);
const tableSortOptions = ref({
  sortBy: props?.sortBy ?? 'company_name',
  orderBy: props?.orderBy ?? 'asc',
});
// #endregion Ref variables

// #region Computed variables
const indeterminate = computed(
  () => selectedTableRows.value.length > 0 && selectedTableRows.value.length < props?.paginatedResults?.data.length
);
const showEditDeleteBtn = computed(() => selectedTableRows.value.length > 0);
const appliedFilterCount = computed(() => {
  let count = 0;
  if (tableFilterOptions.company_name?.length > 0) count++;
  if (tableFilterOptions.show_deleted) count++;
  return count;
});
// #endregion Computed variables

// #region Functions
const onGoToPageClicked = (data) => {
  router.visit(props?.paginatedResults?.path, {
    data: {
      page: data?.page ?? 1,
      perPage: data?.perPage ?? 10,
      ...(tableSortOptions.value && {
        ...tableSortOptions.value,
      }),
      tableFilterOptions: {
        company_name: !!tableFilterOptions?.company_name ? tableFilterOptions.company_name : undefined,
        show_deleted: tableFilterOptions.show_deleted,
      },
    },
    only: ['paginatedResults', 'sortBy', 'orderBy', 'tableFilterOptions'],
    replace: true,
    preserveScroll: true,
  });
};
const onImportDialogCloseClicked = () => {
  isImportDialogOpen.value = false;
};
const onTableHeaderClicked = (title) => {
  const key = tableHeaderTitles.find((th) => th.title === title)?.key;

  let orderBy = 'asc';
  if (key === tableSortOptions.value.sortBy && tableSortOptions.value.orderBy === 'asc') {
    orderBy = 'desc';
  }

  tableSortOptions.value = {
    sortBy: key,
    orderBy,
  };
  onGoToPageClicked({ page: 1, perPage: props?.paginatedResults?.per_page });
};
const onToolbarBtnClicked = (event) => {
  switch (event.action) {
    case 'filter':
      showFilters.value = !showFilters.value;
      break;
    case 'import':
      isImportDialogOpen.value = true;
      break;
    default:
      break;
  }
};
const onResetFiltersClicked = (controlName, value) => {
  if (controlName) {
    tableFilterOptions.defaults(controlName, value);
    tableFilterOptions.reset(controlName);
  } else {
    tableFilterOptions.defaults({
      company_name: '',
      show_deleted: false,
    });
    tableFilterOptions.reset();
  }
  onGoToPageClicked();
};
const onViewDialogCloseClicked = () => {
  isViewDialogOpen.value = false;
};
const onViewItemClicked = (id) => {
  router.post(
    route('admin/infrastructure/companies/view'),
    { id },
    {
      only: ['viewCompany'],
      preserveScroll: true,
      onSuccess: () => {
        isViewDialogOpen.value = true;
      },
    }
  );
};
// #endregion Functions
</script>

<template>
  <CompaniesWrapper>
    <Head title="Overview" />
    <div class="h-full flex flex-col" v-if="paginatedResults?.data.length >= 0">
      <TableToolbar
        :selected-items="selectedTableRows"
        :show-edit-delete-btn="showEditDeleteBtn"
        :add-new-url="addNewUrl"
        :show-filters="showFilters"
        :applied-filter-count="appliedFilterCount"
        :export-url="`${exportUrl}?with_data=1`"
        @button-clicked="onToolbarBtnClicked"
      >
        <template #filter>
          <TransitionRoot
            appear
            :show="showFilters"
            as="div"
            class="mb-4 p-7 border border-gray-200 sm:rounded-lg flex flex-col gap-2 sm:gap-4 md:grid md:grid-cols-2"
            enter="transition duration-300"
            enter-from="opacity-0 -translate-y-4"
            enter-to="opacity-100 translate-y-0"
            leave="transition duration-300"
            leave-from="opacity-100 translate-y-0"
            leave-to="opacity-0 -translate-y-4"
          >
            <div class="grid gap-2">
              <label for="company_name" class="block text-sm font-medium leading-6 text-gray-900">Company Name</label>
              <div class="input-group">
                <input
                  type="text"
                  name="company_name"
                  class="input input-bordered input-sm w-full"
                  v-model="tableFilterOptions.company_name"
                />
                <button
                  type="button"
                  class="btn btn-square btn-outline border-gray-300 btn-sm"
                  @click="() => onResetFiltersClicked('company_name', '')"
                >
                  <XMarkIcon class="h-3 w-3" />
                </button>
              </div>
            </div>
            <div class="grid gap-2">
              <label for="show_deleted" class="block text-sm font-medium leading-6 text-gray-900">Show Deleted</label>
              <input type="checkbox" name="show_deleted" class="toggle" v-model="tableFilterOptions.show_deleted" />
            </div>
            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-2">
              <button type="button" class="btn btn-sm" @click="() => onResetFiltersClicked()">Reset</button>
              <button type="button" class="btn btn-sm btn-primary" @click="() => onGoToPageClicked()">Apply</button>
            </div>
          </TransitionRoot>
        </template>
      </TableToolbar>
      <TableMain>
        <template #thead>
          <tr>
            <th scope="col" class="relative px-7 w-4 sm:w-12 sm:px-6">
              <input
                type="checkbox"
                class="checkbox absolute left-4 top-1/2 -mt-2 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                :checked="
                  indeterminate ||
                  (selectedTableRows.length > 0 && selectedTableRows.length === paginatedResults?.data.length)
                "
                :indeterminate="indeterminate"
                @change="selectedTableRows = $event.target.checked ? paginatedResults?.data.map((c) => c.id) : []"
              />
            </th>
            <template v-for="(header, i) of tableHeaderTitles" :key="header.key">
              <TableSortableHeader
                :title="header.title"
                :toggle-sort="tableSortOptions.sortBy === header.key ? tableSortOptions.orderBy : null"
                :class="[
                  i === 0
                    ? 'pr-3 sm:w-96'
                    : tableHeaderTitles.length > 2 && i < tableHeaderTitles.length - 1
                    ? 'hidden lg:table-cell px-3'
                    : 'hidden sm:table-cell px-3',
                  'py-3.5 text-left text-sm font-semibold text-gray-900',
                ]"
                @on-table-header-clicked="onTableHeaderClicked"
              />
            </template>
            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
              <span class="sr-only">Edit</span>
            </th>
          </tr>
        </template>
        <template #tbody>
          <tr v-if="paginatedResults?.data.length === 0">
            <td colspan="5">
              <NoResults />
            </td>
          </tr>
          <tr
            v-for="company in paginatedResults?.data"
            :key="company.id"
            :class="[selectedTableRows.includes(company.id) && 'bg-gray-50']"
          >
            <td class="relative px-7 w-4 sm:w-12 sm:px-6">
              <div
                v-if="selectedTableRows.includes(company.id)"
                class="absolute inset-y-0 left-0 w-0.5 bg-primary"
              ></div>
              <input
                type="checkbox"
                class="checkbox absolute left-4 top-1/2 -mt-2 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                :value="company.id"
                v-model="selectedTableRows"
              />
            </td>
            <td
              :class="[
                'whitespace-nowrap py-4 pr-3 text-sm font-medium',
                selectedTableRows.includes(company.id) ? 'text-primary' : 'text-gray-900',
              ]"
            >
              <button type="button" class="btn btn-link btn-sm pl-0 normal-case" @click="onViewItemClicked(company.id)">
                <div class="flex gap-2 items-center">
                  <span>{{ company.company_name }}</span>
                  <EyeIcon class="h-5 w-5" />
                </div>
              </button>
              <dl class="font-normal lg:hidden">
                <dt class="sr-only">Active</dt>
                <dd class="mt-1 truncate text-gray-700">
                  <ColouredBadge :data="company.active" data-type="boolean" />
                </dd>
                <dt class="sr-only sm:hidden">Created At</dt>
                <dd class="mt-1 truncate text-gray-500 sm:hidden">
                  {{
                    new Date(company.created_at).toLocaleString('en-SG', {
                      dateStyle: 'medium',
                      timeStyle: 'short',
                    })
                  }}
                </dd>
              </dl>
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
              <ColouredBadge :data="company.active" data-type="boolean" />
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
              {{ new Date(company.created_at).toLocaleString('en-SG', { dateStyle: 'medium', timeStyle: 'short' }) }}
            </td>
            <td class="py-4 pl-3 pr-4 text-right text-sm font-medium">
              <Link :href="route(editUrl, { id: company.id })" class="btn btn-link btn-sm"
                >Edit<span class="sr-only">, {{ company.id }}</span></Link
              >
            </td>
          </tr>
        </template>
      </TableMain>
      <TablePagination
        :paginated-results="paginatedResults"
        @on-go-to-page-clicked="(data) => onGoToPageClicked(data)"
        v-if="paginatedResults?.data.length > 0"
      />
      <TableStickyFooter
        :selected-items="selectedTableRows"
        :show-edit-delete-btn="showEditDeleteBtn"
        :add-new-url="addNewUrl"
        :export-url="`${exportUrl}?with_data=1`"
        @button-clicked="onToolbarBtnClicked"
      />
    </div>
    <Error404 :show="!paginatedResults" />
    <TransitionRoot as="template" :show="isViewDialogOpen">
      <Dialog as="div" class="relative z-40" @close="onViewDialogCloseClicked">
        <div class="fixed inset-0" />

        <div class="fixed inset-0 overflow-hidden">
          <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
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
                  <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                    <div class="sticky top-0 bg-white px-4 py-6 sm:px-6">
                      <div class="flex items-start justify-between">
                        <DialogTitle class="text-base font-semibold leading-6 text-gray-900">View</DialogTitle>
                        <div class="ml-3 flex h-7 items-center">
                          <button
                            type="button"
                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-indigo-500"
                            @click="onViewDialogCloseClicked"
                          >
                            <span class="sr-only">Close panel</span>
                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- Main -->
                    <div class="divide-y divide-gray-200">
                      <div class="pb-6">
                        <div class="h-24 bg-primary sm:h-20 lg:h-28" />
                        <div class="lg:-mt-15 -mt-12 flow-root px-4 sm:-mt-8 sm:flex sm:items-end sm:px-6">
                          <div>
                            <div class="-m-1 flex">
                              <div class="inline-flex overflow-hidden rounded-lg border-4 border-white">
                                <img
                                  v-if="viewCompany?.img_url || viewCompany?.img_path"
                                  :src="
                                    viewCompany?.img_path
                                      ? getImgSrcFromPath(viewCompany?.img_path)
                                      : viewCompany?.img_url
                                  "
                                  class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48 object-cover"
                                />
                                <PhotoIcon
                                  class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48 text-gray-300 bg-white"
                                  aria-hidden="true"
                                  v-else
                                />
                              </div>
                            </div>
                          </div>
                          <div class="mt-6 sm:ml-6 sm:flex-1">
                            <div>
                              <div class="flex flex-col items-start gap-1">
                                <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">
                                  {{ viewCompany.company_name }}
                                </h3>
                                <ColouredBadge :data="viewCompany.active" data-type="boolean" />
                              </div>
                            </div>
                            <div class="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
                              <Link
                                :href="route(editUrl, { id: viewCompany.id })"
                                :replace="true"
                                as="button"
                                class="btn btn-sm btn-block btn-primary sm:max-w-[7rem]"
                              >
                                Edit
                              </Link>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="px-4 py-5 sm:px-0 sm:py-0">
                        <dl class="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
                          <template v-for="label in viewCompanyLabels" :key="label.key">
                            <div class="sm:flex sm:px-6 sm:py-5">
                              <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                                {{ label.title }}
                              </dt>
                              <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                                <template v-if="['img_url', 'website_url'].includes(label.key)">
                                  <a
                                    :href="viewCompany[label.key]"
                                    v-if="viewCompany[label.key]"
                                    class="link"
                                    @click.prevent="openInNewWindow(viewCompany[label.key])"
                                    >{{ viewCompany[label.key] }}</a
                                  >
                                  <span v-else>Unavailable</span>
                                </template>
                                <template v-else>
                                  {{ viewCompany[label.key] ?? 'Unavailable' }}
                                </template>
                              </dd>
                            </div>
                          </template>
                        </dl>
                      </div>
                    </div>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
    <TransitionRoot as="template" :show="isImportDialogOpen">
      <Dialog as="div" class="relative z-40" @close="onImportDialogCloseClicked">
        <div class="fixed inset-0" aria-hidden="true" />

        <div class="fixed inset-0 overflow-hidden">
          <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
              <TransitionChild
                as="template"
                enter="transform transition ease-in-out duration-500 sm:duration-700"
                enter-from="translate-x-full"
                enter-to="translate-x-0"
                leave="transform transition ease-in-out duration-500 sm:duration-700"
                leave-from="translate-x-0"
                leave-to="translate-x-full"
              >
                <DialogPanel class="pointer-events-auto w-screen max-w-2xl">
                  <form class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
                    <div class="h-0 flex-1 overflow-y-auto">
                      <div class="bg-primary px-4 py-6 sm:px-6">
                        <div class="flex items-center justify-between">
                          <DialogTitle class="text-base font-semibold leading-6 text-white">Import CSV</DialogTitle>
                          <div class="ml-3 flex h-7 items-center">
                            <button
                              type="button"
                              class="rounded-md bg-primary text-secondary hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                              @click="onImportDialogCloseClicked"
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
                      <div class="flex flex-1 flex-col justify-between">
                        <div class="divide-y divide-gray-200 px-4 sm:px-6">
                          <div class="space-y-6 pb-5 pt-6">
                            <div>
                              <label for="project-name" class="block text-sm font-medium leading-6 text-gray-900"
                                >Template</label
                              >
                              <div class="mt-2">
                                <a :href="exportUrl" class="btn btn-sm flex gap-2 w-fit">
                                  <ArrowDownCircleIcon class="h-4 w-4" />
                                  <span>Download (.csv)</span>
                                </a>
                              </div>
                            </div>

                            <div>
                              <label for="import_csv" class="block text-sm font-medium leading-6 text-gray-900"
                                >File (.csv)</label
                              >
                              <input
                                type="file"
                                name="import_csv"
                                class="file-input file-input-sm file-input-bordered w-full max-w-xs mt-2"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="grid grid-cols-2 sm:flex sm:flex-shrink-0 gap-2 justify-end px-4 py-4">
                      <button type="button" class="btn sm:grow sm:max-w-[10rem]" @click="onImportDialogCloseClicked">
                        Cancel
                      </button>
                      <button type="button" class="btn btn-primary sm:grow sm:max-w-[10rem]">Import</button>
                    </div>
                  </form>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </CompaniesWrapper>
</template>
