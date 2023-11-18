<script setup>
import { TransitionRoot } from '@headlessui/vue';
import { EyeIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref, watch } from 'vue';
import Error404 from '../../../../Components/AdminPages/Error404.vue';
import NoResults from '../../../../Components/AdminPages/NoResults.vue';
import DialogBulkEdit from '../../../../Components/AdminPages/Overview/DialogBulkEdit.vue';
import DialogImportCsv from '../../../../Components/AdminPages/Overview/DialogImportCsv.vue';
import TableMain from '../../../../Components/AdminPages/Overview/TableMain.vue';
import TablePagination from '../../../../Components/AdminPages/Overview/TablePagination.vue';
import TableSortableHeader from '../../../../Components/AdminPages/Overview/TableSortableHeader.vue';
import TableStickyFooter from '../../../../Components/AdminPages/Overview/TableStickyFooter.vue';
import TableToolbar from '../../../../Components/AdminPages/Overview/TableToolbar.vue';
import CategoryLayout from './CategoryLayout.vue';

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
  viewCategory: Object | undefined,
});
const tableFilterOptions = useForm({
  category_name: props?.tableFilterOptions?.category_name ?? '',
  showDeleted: props?.tableFilterOptions?.showDeleted ?? 'onlyNonDeleted',
});
const importForm = useForm({
  import_file: null,
});
const moduleUrl = 'admin/commerce/categories';
const addNewUrl = `${moduleUrl}/add`;
const editUrl = `${moduleUrl}/edit`;
const exportUrl = `${route('admin/commerce/categories/export')}`;
const tableHeaderTitles = [
  { key: 'category_name', title: 'Category Name' },
  { key: 'category_code', title: 'Category Code' },
  { key: 'subcategories', title: 'Subcategories' },
  { key: 'created_at', title: 'Created At' },
];
const inputFields = [
  { key: 'category_name', title: 'Category Name' },
  { key: 'category_code', title: 'Category Code' },
];
// #endregion Page Variables

// #region Ref variables
const isEditDialogOpen = ref(false);
const editCategoryForms = ref([]);
const editBulkActive = ref(false);
const isImportDialogOpen = ref(false);
const isLoading = ref(false);
const selectedTableRows = ref([]);
const showFilters = ref(false);
const tableSortOptions = ref({
  sortBy: props?.sortBy ?? 'category_name',
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
  if (tableFilterOptions.category_name?.length > 0) count++;
  if (tableFilterOptions.showDeleted !== 'onlyNonDeleted') count++;
  return count;
});
// #endregion Computed variables

// #region Functions
const onBulkEditSaveClicked = () => {
  const bulkEditForm = useForm({ categories: [] });
  bulkEditForm
    .transform(() =>
      editCategoryForms.value.map((category) => ({
        id: category.id,
        category_name: category.category_name ?? '',
        active: !category.active ? false : true,
        subcategories: [],
      }))
    )
    .post(route('admin/commerce/categories/edit.bulk'), {
      onStart: () => (isLoading.value = true),
      onFinish: () => {
        isLoading.value = false;
        onEditDialogCloseClicked(false);
      },
    });
};
const onDeleteRowsClicked = (rows) => {
  router.post(
    route('admin/commerce/categories/delete'),
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
    link.setAttribute('download', 'categories.csv');
    document.body.appendChild(link);
    link.click();
    link.remove();
  });
};
const onEditDialogCloseClicked = (shouldHideAlert = true) => {
  isEditDialogOpen.value = false;
  editCategoryForms.value = [];
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
        category_name: !!tableFilterOptions?.category_name ? tableFilterOptions.category_name : undefined,
        showDeleted: tableFilterOptions.showDeleted,
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
  importForm.post(route('admin/commerce/categories/import'), {
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
      editCategoryForms.value = selectedTableRows.value.map((r) => {
        const category = props.paginatedResults.data.find((d) => d.id === r);
        return useForm({
          id: category.id,
          category_name: category.category_name ?? '',
          active: !category.active ? false : true,
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
      category_name: '',
      showDeleted: 'onlyNonDeleted',
      showActive: 'both',
    });
    tableFilterOptions.reset();
  }
  onGoToPageClicked();
};
const onViewItemClicked = (id) => {
  router.visit(route('admin/commerce/categories/view', { id }));
};
// #endregion Functions

// #region Watchers
watch(editBulkActive, (val) => {
  editCategoryForms.value.forEach((companyForm) => (companyForm.active = val));
});
// #endregion Watchers
</script>

<template>
  <CategoryLayout>
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
              <div class="grid gap-2 col-span-2">
                <label for="category_name" class="block text-sm font-medium leading-6 text-gray-900"
                  >Category Name/ Code</label
                >
                <div class="join">
                  <input
                    type="text"
                    name="category_name"
                    class="input join-item input-bordered input-sm w-full"
                    v-model="tableFilterOptions.category_name"
                  />
                  <button
                    type="button"
                    class="btn join-item btn-square btn-outline border-gray-300 btn-sm"
                    @click="onResetFiltersClicked('category_name', '')"
                  >
                    <XMarkIcon class="h-3 w-3" />
                  </button>
                </div>
              </div>
              <div class="grid gap-2 col-span-2">
                <label for="category_name" class="block text-sm font-medium leading-6 text-gray-900"
                  >Subcategory Name/ Code</label
                >
                <div class="join">
                  <input
                    type="text"
                    name="category_name"
                    class="input join-item input-bordered input-sm w-full"
                    v-model="tableFilterOptions.category_name"
                  />
                  <button
                    type="button"
                    class="btn join-item btn-square btn-outline border-gray-300 btn-sm"
                    @click="onResetFiltersClicked('category_name', '')"
                  >
                    <XMarkIcon class="h-3 w-3" />
                  </button>
                </div>
              </div>
              <div class="col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
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
              </div>
              <div class="col-span-2 mt-4 grid grid-cols-2 sm:grid-cols-3 gap-2">
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
            v-for="category in paginatedResults?.data"
            :key="category.id"
            :class="[
              selectedTableRows.includes(category.id) && 'bg-gray-50',
              !!category.deleted_at && 'bg-red-50 hover:bg-red-100',
              'hover:bg-gray-50',
            ]"
          >
            <td class="relative px-7 w-4 sm:w-12 sm:px-6">
              <div
                v-if="selectedTableRows.includes(category.id)"
                class="absolute inset-y-0 left-0 w-0.5 bg-primary"
              ></div>
              <input
                type="checkbox"
                class="checkbox absolute left-4 top-1/2 -mt-2 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                :value="category.id"
                :disabled="!!category.deleted_at"
                v-model="selectedTableRows"
              />
            </td>
            <td
              :class="[
                'py-4 pr-3 text-sm font-medium',
                selectedTableRows.includes(category.id) ? 'text-primary' : 'text-gray-900',
              ]"
            >
              <button
                type="button"
                class="link text-primary font-semibold text-left"
                @click="onViewItemClicked(category.id)"
              >
                <div class="flex gap-2 items-center">
                  <span :class="category.category_name?.length > 50 ? 'break-all' : 'break-words'">{{
                    category.category_name
                  }}</span>
                  <EyeIcon class="h-5 w-5 flex-shrink-0" />
                </div>
              </button>
              <dl class="font-normal lg:hidden">
                <dt class="sr-only sm:hidden">Created At</dt>
                <dd class="mt-1 truncate text-gray-500 sm:hidden">
                  {{
                    new Date(category.created_at).toLocaleString('en-SG', {
                      dateStyle: 'medium',
                      timeStyle: 'short',
                    })
                  }}
                </dd>
              </dl>
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
              {{ new Date(category.created_at).toLocaleString('en-SG', { dateStyle: 'medium', timeStyle: 'short' }) }}
            </td>
            <td class="py-4 pl-3 pr-4 text-right text-sm font-medium">
              <Link :href="route(editUrl, { id: category.id })" class="btn btn-link btn-sm"
                >Edit<span class="sr-only">, {{ category.id }}</span></Link
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
      context="category"
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
      :edit-forms="editCategoryForms"
      :input-fields="inputFields"
      :is-loading="isLoading"
      editable-header-key="category_name"
      @dialog-close-clicked="onEditDialogCloseClicked"
      @dialog-save-clicked="onBulkEditSaveClicked"
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
  </CategoryLayout>
</template>
