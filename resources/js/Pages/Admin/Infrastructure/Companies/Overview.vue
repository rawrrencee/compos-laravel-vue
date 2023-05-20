<script setup>
import CompaniesWrapper from '@/Pages/Admin/Infrastructure/Companies/CompaniesWrapper.vue';
import { TransitionRoot } from '@headlessui/vue';
import { EyeIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref, watch } from 'vue';
import ColouredBadge from '../../../../Components/AdminPages/ColouredBadge.vue';
import Error404 from '../../../../Components/AdminPages/Error404.vue';
import NoResults from '../../../../Components/AdminPages/NoResults.vue';
import DialogBulkEdit from '../../../../Components/AdminPages/Overview/DialogBulkEdit.vue';
import DialogImportCsv from '../../../../Components/AdminPages/Overview/DialogImportCsv.vue';
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
  show_deleted: props?.tableFilterOptions?.show_deleted ?? 'only_non_deleted',
  show_active: props?.tableFilterOptions?.show_active ?? 'both',
});
const importForm = useForm({
  import_file: null,
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
const inputFields = [
  { key: 'company_name', title: 'Company Name' },
  { key: 'active', title: 'Active' },
  { key: 'address_1', title: 'Address Line 1' },
  { key: 'address_2', title: 'Address Line 2' },
  { key: 'phone_number', title: 'Phone Number' },
  { key: 'mobile_number', title: 'Mobile Number' },
  { key: 'website', title: 'Website URL' },
  { key: 'img_url', title: 'Image URL' },
];
// #endregion Page Variables

// #region Ref variables
const isEditDialogOpen = ref(false);
const editCompanyForms = ref([]);
const editBulkActive = ref(false);
const isImportDialogOpen = ref(false);
const isLoading = ref(false);
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
  if (tableFilterOptions.show_deleted !== 'only_non_deleted') count++;
  if (tableFilterOptions.show_active !== 'both') count++;
  return count;
});
// #endregion Computed variables

// #region Functions
const onBulkEditSaveClicked = () => {
  const bulkEditForm = useForm({ companies: [] });
  bulkEditForm
    .transform(() =>
      editCompanyForms.value.map((company) => ({
        id: company.id,
        company_name: company.company_name ?? '',
        active: !company.active ? false : true,
        address_1: company.address_1 ?? '',
        address_2: company.address_2 ?? '',
        email: company.email ?? '',
        phone_number: company.phone_number ?? '',
        mobile_number: company.mobile_number ?? '',
        website: company.website ?? '',
        img_url: company.img_url ?? '',
      }))
    )
    .post(route('admin/infrastructure/companies/edit.bulk'), {
      onStart: () => (isLoading.value = true),
      onFinish: () => {
        isLoading.value = false;
        onEditDialogCloseClicked(false);
      },
    });
};
const onDeleteRowsClicked = (rows) => {
  router.post(
    route('admin/infrastructure/companies/delete'),
    { ids: rows },
    {
      onStart: () => (isLoading.value = true),
      onFinish: () => {
        isLoading.value = false;
        selectedTableRows.value = [];
      },
    }
  );
};
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
    link.setAttribute('download', 'companies.csv');
    document.body.appendChild(link);
    link.click();
    link.remove();
  });
};
const onEditDialogCloseClicked = (shouldHideAlert = true) => {
  isEditDialogOpen.value = false;
  editCompanyForms.value = [];
  if (shouldHideAlert) usePage().props.flash.show = null;
};
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
        show_active: tableFilterOptions.show_active,
      },
    },
    only: ['paginatedResults', 'sortBy', 'orderBy', 'tableFilterOptions'],
    replace: true,
    preserveScroll: true,
  });
};
const onImportDialogCloseClicked = () => {
  isImportDialogOpen.value = false;
  usePage().props.flash.show = null;
};
const onImportFileAdded = (event) => {
  importForm.import_file = event.target.files[0];
};
const onImportFileSaveClicked = () => {
  importForm.post(route('admin/infrastructure/companies/import'), {
    onStart: () => (isLoading.value = true),
    onFinish: () => (isLoading.value = false),
  });
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
    case 'delete':
      if (
        confirm(
          `Are you sure you want to delete ${selectedTableRows.value.length} record${
            selectedTableRows.value.length === 1 ? '' : 's'
          }?`
        )
      ) {
        onDeleteRowsClicked(selectedTableRows.value);
      }
      break;
    case 'edit':
      editCompanyForms.value = selectedTableRows.value.map((r) => {
        const company = props.paginatedResults.data.find((d) => d.id === r);
        return useForm({
          id: company.id,
          company_name: company.company_name ?? '',
          active: !company.active ? false : true,
          address_1: company.address_1 ?? '',
          address_2: company.address_2 ?? '',
          email: company.email ?? '',
          phone_number: company.phone_number ?? '',
          mobile_number: company.mobile_number ?? '',
          website: company.website ?? '',
          img_url: company.img_url ?? '',
        });
      });
      isEditDialogOpen.value = true;
      break;
    case 'filter':
      showFilters.value = !showFilters.value;
      break;
    case 'import':
      isImportDialogOpen.value = true;
      break;
    case 'export':
      if (confirm('This action may take a long time depending on the amount of data. Do not close the browser.')) {
        onDownloadFileClicked(exportUrl, { with_data: true });
      }
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
      show_deleted: 'only_non_deleted',
      show_active: 'both',
    });
    tableFilterOptions.reset();
  }
  onGoToPageClicked();
};
const onViewItemClicked = (id) => {
  router.visit(route('admin/infrastructure/companies/view', { id }));
};
// #endregion Functions

// #region Watchers
watch(editBulkActive, (val) => {
  editCompanyForms.value.forEach((companyForm) => (companyForm.active = val));
});
// #endregion Watchers
</script>

<template>
  <CompaniesWrapper>
    <Head title="Overview" />
    <div class="h-full flex flex-col sm:px-6 lg:px-8" v-if="paginatedResults?.data.length >= 0">
      <TableToolbar
        :selected-items="selectedTableRows"
        :show-edit-delete-btn="showEditDeleteBtn"
        :add-new-url="addNewUrl"
        :show-filters="showFilters"
        :applied-filter-count="appliedFilterCount"
        :is-loading="isLoading"
        @button-clicked="onToolbarBtnClicked"
      >
        <template #filter>
          <TransitionRoot
            appear
            :show="showFilters"
            as="div"
            enter="transition duration-300"
            enter-from="opacity-0 -translate-y-4"
            enter-to="opacity-100 translate-y-0"
            leave="transition duration-300"
            leave-from="opacity-100 translate-y-0"
            leave-to="opacity-0 -translate-y-4"
          >
            <form
              class="mb-4 p-7 border border-gray-200 sm:rounded-lg flex flex-col gap-2 sm:gap-4 md:grid md:grid-cols-3"
              @submit.prevent="onGoToPageClicked"
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
                    @click="onResetFiltersClicked('company_name', '')"
                  >
                    <XMarkIcon class="h-3 w-3" />
                  </button>
                </div>
              </div>
              <div class="grid gap-2">
                <label for="per_page" class="block text-sm font-medium leading-6 text-gray-900"
                  >Show Deleted Items</label
                >
                <select class="select select-bordered select-sm w-full" v-model="tableFilterOptions.show_deleted">
                  <option value="only_non_deleted">Only non-deleted</option>
                  <option value="only_deleted">Only deleted</option>
                  <option value="both">Both deleted and non-deleted</option>
                </select>
              </div>
              <div class="grid gap-2">
                <label for="per_page" class="block text-sm font-medium leading-6 text-gray-900"
                  >Show Active Items</label
                >
                <select class="select select-bordered select-sm w-full" v-model="tableFilterOptions.show_active">
                  <option value="only_active">Only active</option>
                  <option value="only_non_active">Only non-active</option>
                  <option value="both">Both active and non-active</option>
                </select>
              </div>
              <div class="mt-4 grid grid-cols-2 gap-2">
                <button type="button" class="btn btn-sm" @click="onResetFiltersClicked()">Reset</button>
                <button type="submit" class="btn btn-sm btn-primary">Apply</button>
              </div>
            </form>
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
                @change="
                  selectedTableRows = $event.target.checked
                    ? paginatedResults?.data.filter((c) => !c.deleted_at).map((c) => c.id)
                    : []
                "
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
            :class="[
              selectedTableRows.includes(company.id) && 'bg-gray-50',
              !!company.deleted_at && 'bg-red-50 hover:bg-red-100',
              'hover:bg-gray-50',
            ]"
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
                :disabled="!!company.deleted_at"
                v-model="selectedTableRows"
              />
            </td>
            <td
              :class="[
                'py-4 pr-3 text-sm font-medium',
                selectedTableRows.includes(company.id) ? 'text-primary' : 'text-gray-900',
              ]"
            >
              <button
                type="button"
                class="link text-primary font-semibold text-left"
                @click="onViewItemClicked(company.id)"
              >
                <div class="flex gap-2 items-center">
                  <span :class="company.company_name?.length > 50 ? 'break-all' : 'break-words'">{{
                    company.company_name
                  }}</span>
                  <EyeIcon class="h-5 w-5 flex-shrink-0" />
                </div>
              </button>
              <dl class="font-normal lg:hidden">
                <dt class="sr-only">Active</dt>
                <dd class="mt-2 truncate text-gray-700">
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
        :is-loading="isLoading"
        @button-clicked="onToolbarBtnClicked"
      />
    </div>
    <Error404 :show="!paginatedResults" />
    <DialogImportCsv
      :show="isImportDialogOpen"
      :export-url="exportUrl"
      :import-form="importForm"
      :is-loading="isLoading"
      @on-dialog-close-clicked="onImportDialogCloseClicked"
      @on-dialog-save-clicked="onImportFileSaveClicked"
      @on-import-file-added="onImportFileAdded"
    />
    <DialogBulkEdit
      :show="isEditDialogOpen"
      :selected-items="selectedTableRows"
      :edit-forms="editCompanyForms"
      :input-fields="inputFields"
      :is-loading="isLoading"
      editable-header-key="company_name"
      @on-dialog-close-clicked="onEditDialogCloseClicked"
      @on-dialog-save-clicked="onBulkEditSaveClicked"
    >
      <template #bulk-fields>
        <div class="grid grid-cols-1 px-4 gap-2 pb-6">
          <span class="font-semibold leading-6 text-gray-900">Bulk update</span>
          <div class="flex items-center gap-2">
            <label for="bulk_active" class="block text-sm font-medium leading-6 text-gray-900">Active</label>
            <input type="checkbox" name="bulk_active" class="toggle toggle-primary" v-model="editBulkActive" />
          </div>
        </div>
      </template>
    </DialogBulkEdit>
  </CompaniesWrapper>
</template>
