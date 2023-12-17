<script setup>
import DialogBulkEdit from '@/Components/AdminPages/Overview/DialogBulkEdit.vue';
import { TransitionRoot } from '@headlessui/vue';
import { EyeIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Error404 from '../../../../Components/AdminPages/Error404.vue';
import NoResults from '../../../../Components/AdminPages/NoResults.vue';
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
  category_name_or_code: props?.tableFilterOptions?.category_name_or_code ?? '',
  subcategory_name_or_code: props?.tableFilterOptions?.subcategory_name_or_code ?? '',
  showDeleted: props?.tableFilterOptions?.showDeleted ?? 'onlyNonDeleted',
});
const addNewUrl = `commerce.categories.create`;
const editUrl = `commerce.categories.update`;
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
  if (tableFilterOptions.category_name_or_code?.length > 0) count++;
  if (tableFilterOptions.subcategory_name_or_code?.length > 0) count++;
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
        category_code: category.category_code ?? '',
      }))
    )
    .post(route('commerce.categories.bulkUpdate'), {
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
    route('commerce.categories.delete'),
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
        category_name_or_code: !!tableFilterOptions?.category_name_or_code
          ? tableFilterOptions.category_name_or_code
          : undefined,
        subcategory_name_or_code: !!tableFilterOptions?.subcategory_name_or_code
          ? tableFilterOptions.subcategory_name_or_code
          : undefined,
        showDeleted: tableFilterOptions.showDeleted,
      },
    },
    only: ['paginatedResults', 'sortBy', 'orderBy', 'tableFilterOptions'],
    replace: true,
    preserveScroll: true,
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
          category_code: category.category_code ?? '',
        });
      });
      isEditDialogOpen.value = true;
      break;
    case 'filter':
      showFilters.value = !showFilters.value;
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
      category_name_or_code: '',
      subcategory_name_or_code: '',
      showDeleted: 'onlyNonDeleted',
    });
    tableFilterOptions.reset();
  }
  onGoToPageClicked();
};
const onViewItemClicked = (id) => {
  router.visit(route('commerce.categories.viewReadOnlyPageById', { id }));
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
    <div class="flex h-full flex-col sm:px-6 lg:px-8" v-if="paginatedResults?.data.length >= 0">
      <TableToolbar
        :selected-items="selectedTableRows"
        :show-edit-delete-btn="showEditDeleteBtn"
        :add-new-url="addNewUrl"
        :show-filters="showFilters"
        :applied-filter-count="appliedFilterCount"
        :is-loading="isLoading"
        :show-additional-menu="false"
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
                <label for="category_name" class="block text-sm font-medium leading-6 text-gray-900"
                  >Category Name/ Code</label
                >
                <div class="join">
                  <input
                    type="text"
                    name="category_name_or_code"
                    class="input join-item input-bordered input-sm w-full"
                    v-model="tableFilterOptions.category_name_or_code"
                  />
                  <button
                    type="button"
                    class="btn btn-square btn-outline join-item btn-sm border-gray-300"
                    @click="onResetFiltersClicked('category_name_or_code', '')"
                  >
                    <XMarkIcon class="h-3 w-3" />
                  </button>
                </div>
              </div>
              <div class="col-span-2 grid gap-2">
                <label for="subcategory_name_or_code" class="block text-sm font-medium leading-6 text-gray-900"
                  >Subcategory Name/ Code</label
                >
                <div class="join">
                  <input
                    type="text"
                    name="subcategory_name_or_code"
                    class="input join-item input-bordered input-sm w-full"
                    v-model="tableFilterOptions.subcategory_name_or_code"
                  />
                  <button
                    type="button"
                    class="btn btn-square btn-outline join-item btn-sm border-gray-300"
                    @click="onResetFiltersClicked('subcategory_name_or_code', '')"
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
            v-for="category in paginatedResults?.data"
            :key="category.id"
            :class="[
              selectedTableRows.includes(category.id) && 'bg-gray-50',
              !!category.deleted_at && 'bg-red-50 hover:bg-red-100',
              'hover:bg-gray-50',
            ]"
          >
            <td class="relative w-4 px-7 sm:w-12 sm:px-6">
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
            <td class="py-4 pr-3 text-sm">
              <button
                type="button"
                class="link text-left font-semibold text-primary"
                @click="onViewItemClicked(category.id)"
              >
                <div class="flex items-center gap-2">
                  <span :class="category.category_name?.length > 50 ? 'break-all' : 'break-words'">{{
                    category.category_name
                  }}</span>
                  <EyeIcon class="h-5 w-5 flex-shrink-0" />
                </div>
              </button>
              <dl class="font-normal lg:hidden">
                <dt class="sr-only">Category Code</dt>
                <dd class="mt-2 truncate text-gray-500">
                  {{ category.category_code }}
                </dd>
                <dt class="sr-only">Created At</dt>
                <dd class="mt-2 truncate text-gray-500">
                  {{
                    new Date(category.created_at).toLocaleString('en-SG', {
                      dateStyle: 'medium',
                      timeStyle: 'short',
                    })
                  }}
                </dd>
              </dl>
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
              {{ category.category_code }}
            </td>
            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
              <ul>
                <li v-for="s in category.subcategories.slice(0, 3)">{{ s.subcategory_code }}</li>
                <li v-if="category.subcategories.length > 3">and {{ category.subcategories.length - 3 }} more</li>
              </ul>
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
        :show-additional-menu="false"
        @button-clicked="onToolbarBtnClicked"
      />
    </div>
    <Error404 :show="!paginatedResults" />
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
        <div class="grid grid-cols-1 gap-2 px-4 pb-6">
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
