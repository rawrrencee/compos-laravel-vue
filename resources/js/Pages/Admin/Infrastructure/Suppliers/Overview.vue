<script setup>
import SupplierLayout from '@/Pages/Admin/Infrastructure/Suppliers/SupplierLayout.vue';
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
  viewSupplier: Object | undefined,
});
const tableFilterOptions = useForm({
  supplier_name: props?.tableFilterOptions?.supplier_name ?? '',
  showDeleted: props?.tableFilterOptions?.showDeleted ?? 'onlyNonDeleted',
  showActive: props?.tableFilterOptions?.showActive ?? 'both',
});
const importForm = useForm({
  import_file: null,
});
const moduleUrl = 'admin/infrastructure/suppliers';
const addNewUrl = `${moduleUrl}/add`;
const editUrl = `${moduleUrl}/edit`;
const exportUrl = `${route('admin/infrastructure/suppliers/export')}`;
const tableHeaderTitles = [
  { key: 'supplier_name', title: 'Supplier Name' },
  { key: 'active', title: 'Active' },
  { key: 'created_at', title: 'Created At' },
];
const inputFields = [
  { key: 'supplier_name', title: 'Supplier Name' },
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
const editSupplierForms = ref([]);
const editBulkActive = ref(false);
const isImportDialogOpen = ref(false);
const isLoading = ref(false);
const selectedTableRows = ref([]);
const showFilters = ref(false);
const tableSortOptions = ref({
  sortBy: props?.sortBy ?? 'supplier_name',
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
  if (tableFilterOptions.supplier_name?.length > 0) count++;
  if (tableFilterOptions.showDeleted !== 'onlyNonDeleted') count++;
  if (tableFilterOptions.showActive !== 'both') count++;
  return count;
});
// #endregion Computed variables

// #region Functions
const onBulkEditSaveClicked = () => {
  const bulkEditForm = useForm({ suppliers: [] });
  bulkEditForm
    .transform(() =>
      editSupplierForms.value.map((supplier) => ({
        id: supplier.id,
        supplier_name: supplier.supplier_name ?? '',
        active: !supplier.active ? false : true,
        address_1: supplier.address_1 ?? '',
        address_2: supplier.address_2 ?? '',
        email: supplier.email ?? '',
        phone_number: supplier.phone_number ?? '',
        mobile_number: supplier.mobile_number ?? '',
        website: supplier.website ?? '',
        img_url: supplier.img_url ?? '',
      }))
    )
    .post(route('admin/infrastructure/suppliers/edit.bulk'), {
      onStart: () => (isLoading.value = true),
      onFinish: () => {
        isLoading.value = false;
        if (usePage().props.flash?.status !== 'error') {
          onEditDialogCloseClicked(false);
        }
      },
    });
};
const onDeleteRowsClicked = (rows) => {
  router.post(
    route('admin/infrastructure/suppliers/delete'),
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
    link.setAttribute('download', 'suppliers.csv');
    document.body.appendChild(link);
    link.click();
    link.remove();
  });
};
const onEditDialogCloseClicked = (shouldHideAlert = true) => {
  isEditDialogOpen.value = false;
  editSupplierForms.value = [];
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
        supplier_name: !!tableFilterOptions?.supplier_name ? tableFilterOptions.supplier_name : undefined,
        showDeleted: tableFilterOptions.showDeleted,
        showActive: tableFilterOptions.showActive,
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
  importForm.post(route('admin/infrastructure/suppliers/import'), {
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
      editSupplierForms.value = selectedTableRows.value.map((r) => {
        const supplier = props.paginatedResults.data.find((d) => d.id === r);
        return useForm({
          id: supplier.id,
          supplier_name: supplier.supplier_name ?? '',
          active: !supplier.active ? false : true,
          address_1: supplier.address_1 ?? '',
          address_2: supplier.address_2 ?? '',
          email: supplier.email ?? '',
          phone_number: supplier.phone_number ?? '',
          mobile_number: supplier.mobile_number ?? '',
          website: supplier.website ?? '',
          img_url: supplier.img_url ?? '',
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
      supplier_name: '',
      showDeleted: 'onlyNonDeleted',
      showActive: 'both',
    });
    tableFilterOptions.reset();
  }
  onGoToPageClicked();
};
const onViewItemClicked = (id) => {
  router.visit(route('admin/infrastructure/suppliers/view', { id }));
};
// #endregion Functions

// #region Watchers
watch(editBulkActive, (val) => {
  editSupplierForms.value.forEach((companyForm) => (companyForm.active = val));
});
// #endregion Watchers
</script>

<template>
  <SupplierLayout>
    <Head title="Overview" />
    <div class="flex h-full flex-col sm:px-6 lg:px-8" v-if="paginatedResults?.data.length >= 0">
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
              class="mb-4 flex flex-col gap-2 border border-gray-200 p-7 sm:gap-4 sm:rounded-lg md:grid md:grid-cols-3"
              @submit.prevent="onGoToPageClicked"
            >
              <div class="col-span-2 grid gap-2">
                <label for="supplier_name" class="block text-sm font-medium leading-6 text-gray-900"
                  >Supplier Name</label
                >
                <div class="join">
                  <input
                    type="text"
                    name="supplier_name"
                    class="input join-item input-bordered input-sm w-full"
                    v-model="tableFilterOptions.supplier_name"
                  />
                  <button
                    type="button"
                    class="btn btn-square btn-outline join-item btn-sm border-gray-300"
                    @click="onResetFiltersClicked('supplier_name', '')"
                  >
                    <XMarkIcon class="h-3 w-3" />
                  </button>
                </div>
              </div>
              <div class="col-span-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                <div class="grid gap-2">
                  <label for="per_page" class="block text-sm font-medium leading-6 text-gray-900"
                    >Show Deleted Items</label
                  >
                  <select class="select select-bordered select-sm w-full" v-model="tableFilterOptions.showDeleted">
                    <option value="onlyNonDeleted">Only non-deleted</option>
                    <option value="onlyDeleted">Only deleted</option>
                    <option value="both">Both deleted and non-deleted</option>
                  </select>
                </div>
                <div class="grid gap-2">
                  <label for="per_page" class="block text-sm font-medium leading-6 text-gray-900"
                    >Show Active Items</label
                  >
                  <select class="select select-bordered select-sm w-full" v-model="tableFilterOptions.showActive">
                    <option value="onlyActive">Only active</option>
                    <option value="onlyNonActive">Only non-active</option>
                    <option value="both">Both active and non-active</option>
                  </select>
                </div>
              </div>
              <div class="col-span-2 mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                <button type="button" class="btn btn-sm" @click="onResetFiltersClicked()">Reset</button>
                <button type="submit" class="btn btn-primary btn-sm">Apply</button>
              </div>
            </form>
          </TransitionRoot>
        </template>
      </TableToolbar>
      <TableMain>
        <template #thead>
          <tr>
            <th scope="col" class="relative w-4 px-7 sm:w-12 sm:px-6">
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
                      ? 'hidden px-3 lg:table-cell'
                      : 'hidden px-3 sm:table-cell',
                  'py-3.5 text-left text-sm font-semibold text-gray-900',
                ]"
                @table-header-clicked="onTableHeaderClicked"
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
            v-for="supplier in paginatedResults?.data"
            :key="supplier.id"
            :class="[
              selectedTableRows.includes(supplier.id) && 'bg-gray-50',
              !!supplier.deleted_at && 'bg-red-50 hover:bg-red-100',
              'hover:bg-gray-50',
            ]"
          >
            <td class="relative w-4 px-7 sm:w-12 sm:px-6">
              <div
                v-if="selectedTableRows.includes(supplier.id)"
                class="absolute inset-y-0 left-0 w-0.5 bg-primary"
              ></div>
              <input
                type="checkbox"
                class="checkbox absolute left-4 top-1/2 -mt-2 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                :value="supplier.id"
                :disabled="!!supplier.deleted_at"
                v-model="selectedTableRows"
              />
            </td>
            <td class="py-4 pr-3 text-sm">
              <button
                type="button"
                class="link text-left font-semibold text-primary"
                @click="onViewItemClicked(supplier.id)"
              >
                <div class="flex items-center gap-2">
                  <span :class="supplier.supplier_name?.length > 50 ? 'break-all' : 'break-words'">{{
                    supplier.supplier_name
                  }}</span>
                  <EyeIcon class="h-5 w-5 flex-shrink-0" />
                </div>
              </button>
              <dl class="font-normal lg:hidden">
                <dt class="sr-only">Active</dt>
                <dd class="mt-2 truncate text-gray-700">
                  <ColouredBadge :data="supplier.active" data-type="boolean" />
                </dd>
                <dt class="sr-only sm:hidden">Created At</dt>
                <dd class="mt-2 truncate text-gray-500 sm:hidden">
                  {{
                    new Date(supplier.created_at).toLocaleString('en-SG', {
                      dateStyle: 'medium',
                      timeStyle: 'short',
                    })
                  }}
                </dd>
              </dl>
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
              <ColouredBadge :data="supplier.active" data-type="boolean" />
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
              {{ new Date(supplier.created_at).toLocaleString('en-SG', { dateStyle: 'medium', timeStyle: 'short' }) }}
            </td>
            <td class="py-4 pl-3 pr-4 text-right text-sm font-medium">
              <Link :href="route(editUrl, { id: supplier.id })" class="btn btn-link btn-sm"
                >Edit<span class="sr-only">, {{ supplier.id }}</span></Link
              >
            </td>
          </tr>
        </template>
      </TableMain>
      <TablePagination
        :paginated-results="paginatedResults"
        @go-to-page-clicked="(data) => onGoToPageClicked(data)"
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
      context="supplier"
      :show="isImportDialogOpen"
      :export-url="exportUrl"
      :import-form="importForm"
      :is-loading="isLoading"
      @dialog-close-clicked="onImportDialogCloseClicked"
      @dialog-save-clicked="onImportFileSaveClicked"
      @import-file-added="onImportFileAdded"
    />
    <DialogBulkEdit
      :show="isEditDialogOpen"
      :selected-items="selectedTableRows"
      :edit-forms="editSupplierForms"
      :input-fields="inputFields"
      :is-loading="isLoading"
      editable-header-key="supplier_name"
      @dialog-close-clicked="onEditDialogCloseClicked"
      @dialog-save-clicked="onBulkEditSaveClicked"
    >
      <template #bulk-fields>
        <div class="grid grid-cols-1 gap-2 px-4 pb-6">
          <span class="font-semibold leading-6 text-gray-900">Bulk update</span>
          <div class="flex items-center gap-2">
            <label for="bulk_active" class="block text-sm font-medium leading-6 text-gray-900">Active</label>
            <input type="checkbox" name="bulk_active" class="toggle toggle-primary" v-model="editBulkActive" />
          </div>
        </div>
      </template>
    </DialogBulkEdit>
  </SupplierLayout>
</template>
