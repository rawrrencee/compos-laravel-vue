<script setup>
import CompaniesWrapper from "@/Pages/Admin/Infrastructure/Companies/CompaniesWrapper.vue";
import { TransitionRoot } from "@headlessui/vue";
import { EyeIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import ColouredBadge from "../../../../Components/AdminPages/ColouredBadge.vue";
import Error404 from "../../../../Components/AdminPages/Error404.vue";
import NoResults from "../../../../Components/AdminPages/NoResults.vue";
import TableMain from "../../../../Components/AdminPages/Overview/TableMain.vue";
import TablePagination from "../../../../Components/AdminPages/Overview/TablePagination.vue";
import TableSortableHeader from "../../../../Components/AdminPages/Overview/TableSortableHeader.vue";
import TableStickyFooter from "../../../../Components/AdminPages/Overview/TableStickyFooter.vue";
import TableToolbar from "../../../../Components/AdminPages/Overview/TableToolbar.vue";

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
});
const tableFilterOptions = useForm({
    company_name: props?.tableFilterOptions?.company_name ?? "",
    show_deleted:
        props?.tableFilterOptions?.show_deleted === null
            ? false
            : !!props?.tableFilterOptions?.show_deleted,
});
const moduleUrl = "admin/infrastructure/companies";
const addNewUrl = `${moduleUrl}/add`;
const editUrl = `${moduleUrl}/edit`;
const tableHeaderTitles = [
    { id: "company_name", title: "Company Name" },
    { id: "active", title: "Active" },
    { id: "created_at", title: "Created At" },
];
// #endregion Page Variables

// #region Ref variables
const showFilters = ref(false);
const tableSortOptions = ref({
    sortBy: props?.sortBy ?? "company_name",
    orderBy: props?.orderBy ?? "asc",
});
const selectedTableRows = ref([]);
// #endregion Ref variables

// #region Computed variables
const indeterminate = computed(
    () =>
        selectedTableRows.value.length > 0 &&
        selectedTableRows.value.length < props?.paginatedResults?.data.length
);
const showEditDeleteBtn = computed(() => selectedTableRows.value.length > 0);
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
                company_name: !!tableFilterOptions?.company_name
                    ? tableFilterOptions.company_name
                    : undefined,
                show_deleted: tableFilterOptions.show_deleted,
            },
        },
        only: ["paginatedResults", "sortBy", "orderBy", "tableFilterOptions"],
        replace: true,
        preserveScroll: true,
    });
};
const onTableHeaderClicked = (title) => {
    const id = tableHeaderTitles.find((th) => th.title === title)?.id;

    let orderBy = "asc";
    if (
        id === tableSortOptions.value.sortBy &&
        tableSortOptions.value.orderBy === "asc"
    ) {
        orderBy = "desc";
    }

    tableSortOptions.value = {
        sortBy: id,
        orderBy,
    };
    onGoToPageClicked({ page: 1, perPage: props?.paginatedResults?.per_page });
};
const onToolbarBtnClicked = (event) => {
    switch (event.action) {
        case "filter":
            showFilters.value = !showFilters.value;
            break;
        default:
            break;
    }
};

const resetFilters = (controlName, value) => {
    if (controlName) {
        tableFilterOptions.defaults(controlName, value);
        tableFilterOptions.reset(controlName);
    } else {
        tableFilterOptions.defaults({
            company_name: "",
            show_deleted: false,
        });
        tableFilterOptions.reset();
    }
    onGoToPageClicked();
};
// #endregion Functions
</script>

<template>
    <CompaniesWrapper>
        <Head title="Overview" />
        <div
            class="h-full flex flex-col"
            v-if="paginatedResults?.data.length >= 0"
        >
            <TableToolbar
                :selected-items="selectedTableRows"
                :show-edit-delete-btn="showEditDeleteBtn"
                :add-new-url="addNewUrl"
                :show-filters="showFilters"
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
                            <label
                                for="company_name"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Company Name</label
                            >
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
                                    @click="
                                        () => resetFilters('company_name', '')
                                    "
                                >
                                    <XMarkIcon class="h-3 w-3" />
                                </button>
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <label
                                for="show_deleted"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Show Deleted</label
                            >
                            <input
                                type="checkbox"
                                name="show_deleted"
                                class="toggle"
                                v-model="tableFilterOptions.show_deleted"
                            />
                        </div>
                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <button
                                type="button"
                                class="btn btn-sm"
                                @click="() => resetFilters()"
                            >
                                Reset
                            </button>
                            <button
                                type="button"
                                class="btn btn-sm btn-primary"
                                @click="() => onGoToPageClicked()"
                            >
                                Apply
                            </button>
                        </div>
                    </TransitionRoot>
                </template>
            </TableToolbar>
            <TableMain>
                <template #thead>
                    <tr>
                        <th
                            scope="col"
                            class="relative px-7 w-4 sm:w-12 sm:px-6"
                        >
                            <input
                                type="checkbox"
                                class="checkbox absolute left-4 top-1/2 -mt-2 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                                :checked="
                                    indeterminate ||
                                    (selectedTableRows.length > 0 &&
                                        selectedTableRows.length ===
                                            paginatedResults?.data.length)
                                "
                                :indeterminate="indeterminate"
                                @change="
                                    selectedTableRows = $event.target.checked
                                        ? paginatedResults?.data.map(
                                              (c) => c.id
                                          )
                                        : []
                                "
                            />
                        </th>
                        <template
                            v-for="(header, i) of tableHeaderTitles"
                            :key="header.id"
                        >
                            <TableSortableHeader
                                :title="header.title"
                                :toggle-sort="
                                    tableSortOptions.sortBy === header.id
                                        ? tableSortOptions.orderBy
                                        : null
                                "
                                :class="[
                                    i === 0
                                        ? 'pr-3 sm:w-96'
                                        : tableHeaderTitles.length > 2 &&
                                          i < tableHeaderTitles.length - 1
                                        ? 'hidden lg:table-cell px-3'
                                        : 'hidden sm:table-cell px-3',
                                    'py-3.5 text-left text-sm font-semibold text-gray-900',
                                ]"
                                @on-table-header-clicked="onTableHeaderClicked"
                            />
                        </template>
                        <th
                            scope="col"
                            class="relative py-3.5 pl-3 pr-4 sm:pr-3"
                        >
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
                            selectedTableRows.includes(company.id) &&
                                'bg-gray-50',
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
                                v-model="selectedTableRows"
                            />
                        </td>
                        <td
                            :class="[
                                'whitespace-nowrap py-4 pr-3 text-sm font-medium',
                                selectedTableRows.includes(company.id)
                                    ? 'text-primary'
                                    : 'text-gray-900',
                            ]"
                        >
                            <a
                                href="#"
                                class="btn btn-link btn-sm pl-0 normal-case"
                            >
                                <div class="flex gap-2 items-center">
                                    <span>{{ company.company_name }}</span>
                                    <EyeIcon class="h-5 w-5" />
                                </div>
                            </a>
                            <dl class="font-normal lg:hidden">
                                <dt class="sr-only">Active</dt>
                                <dd class="mt-1 truncate text-gray-700">
                                    <ColouredBadge
                                        :data="company.active"
                                        data-type="boolean"
                                    />
                                </dd>
                                <dt class="sr-only sm:hidden">Created At</dt>
                                <dd
                                    class="mt-1 truncate text-gray-500 sm:hidden"
                                >
                                    {{
                                        new Date(
                                            company.created_at
                                        ).toLocaleString("en-SG", {
                                            dateStyle: "medium",
                                            timeStyle: "short",
                                        })
                                    }}
                                </dd>
                            </dl>
                        </td>
                        <td
                            class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell"
                        >
                            <ColouredBadge
                                :data="company.active"
                                data-type="boolean"
                            />
                        </td>
                        <td
                            class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell"
                        >
                            {{
                                new Date(company.created_at).toLocaleString(
                                    "en-SG",
                                    { dateStyle: "medium", timeStyle: "short" }
                                )
                            }}
                        </td>
                        <td
                            class="py-4 pl-3 pr-4 text-right text-sm font-medium"
                        >
                            <Link
                                :href="route(editUrl, { id: company.id })"
                                class="btn btn-link btn-sm"
                                >Edit<span class="sr-only"
                                    >, {{ company.id }}</span
                                ></Link
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
                @button-clicked="onToolbarBtnClicked"
            />
        </div>
        <Error404 :show="!paginatedResults" />
    </CompaniesWrapper>
</template>
