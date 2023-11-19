<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import ColouredBadge from '@/Components/AdminPages/ColouredBadge.vue';
import NoResults from '@/Components/AdminPages/NoResults.vue';
import TableMain from '@/Components/AdminPages/Overview/TableMain.vue';
import TablePagination from '@/Components/AdminPages/Overview/TablePagination.vue';
import TableSortableHeader from '@/Components/AdminPages/Overview/TableSortableHeader.vue';
import TableStickyFooter from '@/Components/AdminPages/Overview/TableStickyFooter.vue';
import TableToolbar from '@/Components/AdminPages/Overview/TableToolbar.vue';
import EmployeesLayout from '@/Pages/Admin/Users/Employees/EmployeesLayout.vue';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { EyeIcon, LockClosedIcon, PencilIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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
  employeeRequestKey: Object,
  employeeRequestStatuses: Array,
  viewEmployeeRequest: Object,
});
const employeeRequestKeyForm = useForm({
  global_value: props.employeeRequestKey?.global_value ?? '',
});
const tableFilterOptions = useForm({
  searchTerm: props?.tableFilterOptions?.searchTerm ?? '',
  status: props?.tableFilterOptions?.status ?? '',
  showDeleted: props?.tableFilterOptions?.showDeleted ?? 'onlyNonDeleted',
});
const tableHeaderTitles = [
  { key: 'employee_name', title: 'Employee Name' },
  { key: 'preferred_name', title: 'Preferred Name' },
  { key: 'email', title: 'Email' },
  { key: 'status', title: 'Status' },
  { key: 'created_at', title: 'Created At' },
];
const employeeRequestFields = new Map([
  ['username', 'Username'],
  ['email', 'Email'],
  ['password', 'Password'],
  ['commencement_date', 'Commencement Date'],
  ['first_name', 'First Name'],
  ['middle_name', 'Middle Name'],
  ['last_name', 'Last Name'],
  ['preferred_name', 'Preferred Name'],
  ['nric', 'NRIC'],
  ['phone_number', 'Phone Number'],
  ['mobile_number', 'Mobile Number'],
  ['address_1', 'Address Line 1'],
  ['address_2', 'Address Line 2'],
  ['address_3', 'Address Line 3'],
  ['address_4', 'Address Line 4'],
  ['city', 'City'],
  ['state', 'State'],
  ['postal_code', 'Postal Code'],
  ['country', 'Country'],
  ['gender', 'Gender'],
  ['race', 'Race'],
  ['ethnic_name', 'Ethnic Name'],
  ['date_of_birth', 'Date Of Birth'],
  ['nationality', 'Nationality'],
  ['residency_status', 'Residency Status'],
  ['pr_conversion_date', 'PR Conversion Date'],
  ['emergency_name', 'Emergency Name'],
  ['emergency_relationship', 'Emergency Relationship'],
  ['emergency_address_1', 'Emergency Address Line 1'],
  ['emergency_address_2', 'Emergency Address Line 2'],
  ['emergency_address_3', 'Emergency Address Line 3'],
  ['emergency_address_4', 'Emergency Address Line 4'],
  ['emergency_contact_number', 'Emergency Contact Number'],
  ['bank_name', 'Bank Name'],
  ['bank_account_number', 'Bank Account Number'],
  ['remarks', 'Remarks'],
]);
const employeeRequestForm = useForm(
  Object.assign(
    {},
    ...[
      ...Array.from(employeeRequestFields, ([key]) => ({ [key]: props.viewEmployeeRequest?.[key] ?? '' })),
      { status: props.viewEmployeeRequest?.status },
    ]
  )
);
// #endregion Page Variables

// #region Ref variables
const editEmployeeRequestKey = ref(false);
const isLoading = ref(false);
const isViewRequestDialogOpen = ref(!!props.viewEmployeeRequest);
const isEditingRequestStatus = ref(false);
const selectedTableRows = ref([]);
const showFilters = ref(false);
const tableSortOptions = ref({
  sortBy: props?.sortBy ?? 'created_at',
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
  if (tableFilterOptions.searchTerm?.length > 0) count++;
  if (tableFilterOptions.status) count++;
  if (tableFilterOptions.showDeleted !== 'onlyNonDeleted') count++;
  return count;
});
// #endregion Computed variables

// #region Functions
const onEditEmployeeRequestSaveClicked = () => {
  employeeRequestKeyForm.post(route('admin/users/employees/requests.key-update'), {
    onStart: () => (isLoading.value = true),
    onFinish: () => {
      isLoading.value = false;
      if (usePage().props.flash?.status !== 'error') {
        editEmployeeRequestKey.value = false;
      }
    },
  });
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
        searchTerm: !!tableFilterOptions?.searchTerm ? tableFilterOptions.searchTerm : undefined,
        status: tableFilterOptions.status,
        showDeleted: tableFilterOptions.showDeleted,
      },
    },
    only: ['paginatedResults', 'sortBy', 'orderBy', 'tableFilterOptions', 'viewEmployeeRequest'],
    replace: true,
    preserveScroll: true,
  });
};

const onResetFiltersClicked = (controlName, value) => {
  if (controlName) {
    tableFilterOptions.defaults(controlName, value);
    tableFilterOptions.reset(controlName);
  } else {
    tableFilterOptions.defaults({
      searchTerm: '',
      status: '',
      showDeleted: 'onlyNonDeleted',
    });
    tableFilterOptions.reset();
  }
  onGoToPageClicked();
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
    default:
      break;
  }
};

const onViewItemClicked = (id) => {
  router.get(
    route('admin/users/employees/requests/view'),
    { id },
    {
      onStart: () => {},
      onFinish: () => {
        isViewRequestDialogOpen.value = true;
      },
      only: ['viewEmployeeRequest'],
      preserveScroll: true,
    }
  );
};

const onViewRequestCloseClicked = (val) => {
  isViewRequestDialogOpen.value = val;
  if (props?.paginatedResults?.path) {
    onGoToPageClicked();
  } else {
    router.get(route('admin/users/employees/requests'));
  }
};
// #endregion Functions
</script>

<template>
  <EmployeesLayout>
    <Head title="Employee Requests" />
    <div class="flex h-full flex-col gap-4 sm:px-6 lg:px-8">
      <div class="flex flex-col gap-2 px-6 py-4 sm:px-0">
        <label for="employee_request" class="text-md block font-semibold leading-6 text-gray-900"
          >Employee Request Key</label
        >
        <small class="font-medium text-secondary"
          >This is the key required for submitting an Employee Request to create a new Employee account.</small
        >
        <form
          class="flex flex-col gap-2"
          v-if="editEmployeeRequestKey"
          @submit.prevent="onEditEmployeeRequestSaveClicked"
        >
          <div class="flex flex-col gap-1">
            <input
              id="employee_request"
              type="text"
              name="employee_request"
              class="input join-item input-bordered input-sm w-full max-w-md"
              :class="employeeRequestKeyForm.errors?.global_value ? 'border-error' : ''"
              v-model="employeeRequestKeyForm.global_value"
            />
            <span v-if="employeeRequestKeyForm.errors?.global_value" class="text-error">
              {{ employeeRequestKeyForm.errors.global_value }}
            </span>
          </div>
          <div class="flex flex-row gap-2">
            <button type="submit" class="btn btn-primary btn-sm max-w-[8rem] grow">Save</button
            ><button type="button" class="btn btn-sm max-w-[8rem] grow" @click="editEmployeeRequestKey = false">
              Cancel
            </button>
          </div>
        </form>
        <template v-else>
          <div class="flex flex-col gap-2 sm:flex-row sm:gap-5">
            <div class="flex flex-row items-center gap-4 text-sm font-normal leading-6 text-gray-900">
              <LockClosedIcon class="h-3 w-3" />
              <span> {{ employeeRequestKey?.global_value ?? 'Not Set' }}</span>
            </div>
            <button type="button" class="btn btn-primary btn-sm sm:btn-xs" @click="editEmployeeRequestKey = true">
              <div class="flex items-center gap-2">
                <PencilIcon class="h-3 w-3" />
                <span>Edit</span>
              </div>
            </button>
          </div>
        </template>
      </div>
      <div class="flex flex-grow flex-col gap-2">
        <span class="text-md block px-6 font-semibold leading-6 text-gray-900 sm:px-0">Employee Requests</span>

        <div class="flex flex-grow flex-col">
          <TableToolbar
            :selected-items="selectedTableRows"
            :show-edit-delete-btn="showEditDeleteBtn"
            :show-filters="showFilters"
            :applied-filter-count="appliedFilterCount"
            :is-loading="isLoading"
            :is-bulk-editable="false"
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
                    <label for="searchTerm" class="block text-sm font-medium leading-6 text-gray-900"
                      >Search Name/ Email</label
                    >
                    <div class="join">
                      <input
                        type="text"
                        name="searchTerm"
                        class="input join-item input-bordered input-sm w-full"
                        v-model="tableFilterOptions.searchTerm"
                      />
                      <button
                        type="button"
                        class="btn btn-square btn-outline join-item btn-sm border-gray-300"
                        @click="onResetFiltersClicked('searchTerm', '')"
                      >
                        <XMarkIcon class="h-3 w-3" />
                      </button>
                    </div>
                  </div>
                  <div class="col-span-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <div class="grid gap-2">
                      <label for="per_page" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                      <select class="select select-bordered select-sm w-full" v-model="tableFilterOptions.status">
                        <option value="">All statuses</option>
                        <option v-for="(status, index) in employeeRequestStatuses" :key="index" :value="status">
                          {{ status }}
                        </option>
                      </select>
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
                    :class="[i === 0 ? 'pr-3' : 'hidden px-3 md:table-cell']"
                    class="py-3.5 text-left text-sm font-semibold text-gray-900"
                    @table-header-clicked="onTableHeaderClicked"
                  />
                </template>
              </tr>
            </template>
            <template #tbody>
              <tr v-if="paginatedResults?.data.length === 0">
                <td colspan="6">
                  <NoResults />
                </td>
              </tr>

              <tr
                v-for="request in paginatedResults?.data"
                :key="request.id"
                :class="[
                  selectedTableRows.includes(request.id) && 'bg-gray-50',
                  !!request.deleted_at && 'bg-red-50 hover:bg-red-100',
                  'hover:bg-gray-50',
                ]"
              >
                <td class="relative w-4 px-7 sm:w-12 sm:px-6">
                  <div
                    v-if="selectedTableRows.includes(request.id)"
                    class="absolute inset-y-0 left-0 w-0.5 bg-primary"
                  ></div>
                  <input
                    type="checkbox"
                    class="checkbox absolute left-4 top-1/2 -mt-2 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                    :value="request.id"
                    :disabled="!!request.deleted_at"
                    v-model="selectedTableRows"
                  />
                </td>
                <td class="py-4 pr-3 text-sm">
                  <button
                    type="button"
                    class="link text-left font-semibold text-primary"
                    @click="onViewItemClicked(request.id)"
                  >
                    <div class="flex items-center gap-2">
                      <span :class="request.employee_name?.length > 50 ? 'break-all' : 'break-words'">{{
                        request.employee_name
                      }}</span>
                      <EyeIcon class="h-5 w-5 flex-shrink-0" />
                    </div>
                  </button>
                  <dl class="font-normal md:hidden">
                    <dt class="sr-only">Preferred Name</dt>
                    <dd class="mt-2 truncate text-gray-700">
                      {{ request.preferred_name ?? '-' }}
                    </dd>
                    <dt class="sr-only">Email</dt>
                    <dd class="mt-2 truncate text-gray-700">
                      {{ request.email }}
                    </dd>
                    <dt class="sr-only">Status</dt>
                    <dd class="mt-2 truncate text-gray-700">
                      <ColouredBadge :data="request.status" data-type="enum" />
                    </dd>
                    <dt class="sr-only sm:hidden">Created At</dt>
                    <dd class="mt-2 truncate text-gray-500">
                      {{
                        new Date(request.created_at).toLocaleString('en-SG', {
                          dateStyle: 'medium',
                          timeStyle: 'short',
                        })
                      }}
                    </dd>
                  </dl>
                </td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                  {{ request.preferred_name ?? '-' }}
                </td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">{{ request.email }}</td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                  <ColouredBadge :data="request.status" data-type="enum" />
                </td>
                <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                  {{
                    new Date(request.created_at).toLocaleString('en-SG', { dateStyle: 'medium', timeStyle: 'short' })
                  }}
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
            :is-loading="isLoading"
            @button-clicked="onToolbarBtnClicked"
          />
        </div>
      </div>
    </div>

    <TransitionRoot as="template" :show="isViewRequestDialogOpen">
      <Dialog as="div" class="relative z-50" @close="onViewRequestCloseClicked">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-hidden">
          <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex">
              <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to="opacity-100 translate-y-0 sm:scale-100"
                leave="ease-in duration-200"
                leave-from="opacity-100 translate-y-0 sm:scale-100"
                leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              >
                <DialogPanel
                  class="pointer-events-auto relative w-screen transform overflow-x-hidden overflow-y-scroll rounded-lg bg-white px-4 pt-5 text-left shadow-xl transition-all sm:m-8 sm:max-w-xl sm:px-6"
                >
                  <div class="flex items-start justify-between pb-6">
                    <span class="text-lg font-semibold leading-6 text-gray-900"> View Employee Request </span>
                    <div class="ml-3 flex h-7 items-center">
                      <button
                        type="button"
                        class="relative rounded-md bg-white text-gray-400 hover:text-gray-500"
                        @click="onViewRequestCloseClicked(false)"
                      >
                        <span class="absolute -inset-2.5" />
                        <span class="sr-only">Close panel</span>
                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                      </button>
                    </div>
                  </div>
                  <div class="mb-8 flex flex-col gap-2">
                    <template v-if="isEditingRequestStatus">
                      <label for="employeeRequestStatus" class="block text-sm font-medium leading-6 text-gray-900"
                        >Status</label
                      >
                      <select
                        id="employeeRequestStatus"
                        class="select select-bordered w-full"
                        v-model="employeeRequestForm.status"
                      >
                        <option v-for="(status, index) in employeeRequestStatuses" :key="index" :value="status">
                          {{ status }}
                        </option>
                      </select>
                    </template>
                    <template v-else>
                      <span class="text-md font-semibold leading-6 text-gray-900">Status</span>
                      <div class="flex flex-row items-center gap-4">
                        <ColouredBadge data-type="enum" :data="viewEmployeeRequest.status" />
                        <button type="button" class="btn btn-outline btn-xs" @click="isEditingRequestStatus = true">
                          <div class="flex items-center gap-2">
                            <PencilIcon class="h-3 w-3" />
                            <span>Edit</span>
                          </div>
                        </button>
                      </div>
                    </template>
                  </div>
                  <div class="flex flex-col gap-4 pb-8">
                    <div class="flex flex-col gap-1" v-for="field in employeeRequestFields.entries()">
                      <template
                        v-if="
                          [
                            'commencement_date',
                            'nric',
                            'address_1',
                            'gender',
                            'date_of_birth',
                            'emergency_name',
                            'bank_name',
                            'remarks',
                          ].includes(field[0])
                        "
                      >
                        <div class="divider pt-10"></div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'commencement_date'">
                          Main Information
                        </div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'nric'">Contact Info</div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'address_1'">
                          Location Information
                        </div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'gender'">
                          Ethnic Information
                        </div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'date_of_birth'">
                          Nationality Information
                        </div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'emergency_name'">
                          Emergency Information
                        </div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'bank_name'">
                          Bank Information
                        </div>
                        <div class="pb-10 text-lg font-semibold leading-6" v-if="field[0] === 'remarks'">
                          Other Information
                        </div>
                      </template>

                      <label :for="field[0]" class="block text-sm font-medium leading-6 text-gray-900">{{
                        field[1]
                      }}</label>
                      <div class="flex flex-col gap-1">
                        <input
                          type="text"
                          :id="field[0]"
                          :name="field[0]"
                          v-model="employeeRequestForm[field[0]]"
                          class="input input-bordered w-full"
                          :class="[employeeRequestForm.errors[field[0]] ? 'border-error' : '']"
                          @input="() => employeeRequestForm.clearErrors(field[0])"
                        />
                        <span v-if="employeeRequestForm.errors[field[0]]" class="text-error">
                          {{ employeeRequestForm.errors[field[0]] }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <StickyFooter
                    cancel-button-type="button"
                    cancel-button-text="Discard Changes"
                    save-button-type="button"
                    save-button-text="Save and Approve"
                    @cancel-clicked="onViewRequestCloseClicked(false)"
                  />
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </EmployeesLayout>
</template>
