<script setup>
import EmployeesWrapper from "@/Pages/Admin/Users/Employees/EmployeesWrapper.vue";
import { TransitionRoot } from "@headlessui/vue";
import {
    ArrowLeftIcon,
    ArrowRightIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import TableMain from "../../../../Components/AdminPages/Overview/TableMain.vue";
import TableStickyFooter from "../../../../Components/AdminPages/Overview/TableStickyFooter.vue";
import TableToolbar from "../../../../Components/AdminPages/Overview/TableToolbar.vue";

const people = [
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
];

const addNewUrl = "admin/users/employees/add";
const selectedPeople = ref([]);
const indeterminate = computed(
    () =>
        selectedPeople.value.length > 0 &&
        selectedPeople.value.length < people.length
);
const showEditDeleteBtn = computed(() => selectedPeople.value.length > 0);
const onToolbarBtnClicked = (event) => {
    console.log("tableToolbarBtnClicked", event);
};
</script>

<template>
    <EmployeesWrapper>
        <Head title="Overview" />
        <TransitionRoot
            appear
            :show="true"
            as="div"
            class="h-full flex flex-col"
            enter="transition-opacity duration-500"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="transition-opacity duration-300"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
            <TableToolbar
                :selected-items="selectedPeople"
                :show-edit-delete-btn="showEditDeleteBtn"
                :add-new-url="addNewUrl"
                @button-clicked="onToolbarBtnClicked"
            />
            <TableMain>
                <template #thead>
                    <tr>
                        <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                            <input
                                type="checkbox"
                                class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                :checked="
                                    indeterminate ||
                                    selectedPeople.length === people.length
                                "
                                :indeterminate="indeterminate"
                                @change="
                                    selectedPeople = $event.target.checked
                                        ? people.map((p) => p.email)
                                        : []
                                "
                            />
                        </th>
                        <th
                            scope="col"
                            class="py-3.5 pr-3 text-left text-sm font-semibold text-gray-900"
                        >
                            Name
                        </th>
                        <th
                            scope="col"
                            class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                        >
                            Designation
                        </th>
                        <th
                            scope="col"
                            class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell"
                        >
                            Email
                        </th>
                        <th
                            scope="col"
                            class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell"
                        >
                            Active
                        </th>
                        <th
                            scope="col"
                            class="relative py-3.5 pl-3 pr-4 sm:pr-3"
                        >
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </template>
                <template #tbody>
                    <tr
                        v-for="person in people"
                        :key="person.email"
                        :class="[
                            selectedPeople.includes(person.email) &&
                                'bg-gray-50',
                        ]"
                    >
                        <td class="relative px-7 sm:w-12 sm:px-6">
                            <div
                                v-if="selectedPeople.includes(person.email)"
                                class="absolute inset-y-0 left-0 w-0.5 bg-primary"
                            ></div>
                            <input
                                type="checkbox"
                                class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                :value="person.email"
                                v-model="selectedPeople"
                            />
                        </td>
                        <td
                            :class="[
                                'whitespace-nowrap py-4 pr-3 text-sm font-medium',
                                selectedPeople.includes(person.email)
                                    ? 'text-primary'
                                    : 'text-gray-900',
                            ]"
                        >
                            <a
                                href="#"
                                class="btn btn-link btn-sm pl-0 normal-case"
                            >
                                <div class="flex gap-2 items-center">
                                    <span>{{ person.name }}</span>
                                    <EyeIcon class="h-5 w-5" />
                                </div>
                            </a>
                            <dl class="font-normal lg:hidden">
                                <dt class="sr-only">Title</dt>
                                <dd class="mt-1 truncate text-gray-700">
                                    {{ person.title }}
                                </dd>
                                <dt class="sr-only sm:hidden">Email</dt>
                                <dd
                                    class="mt-1 truncate text-gray-500 sm:hidden"
                                >
                                    {{ person.email }}
                                </dd>
                                <dt class="sr-only sm:hidden">Role</dt>
                                <dd
                                    class="mt-1 truncate text-gray-500 sm:hidden"
                                >
                                    {{ person.role }}
                                </dd>
                            </dl>
                        </td>
                        <td
                            class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell"
                        >
                            {{ person.title }}
                        </td>
                        <td
                            class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell"
                        >
                            {{ person.email }}
                        </td>
                        <td
                            class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell"
                        >
                            {{ person.role }}
                        </td>
                        <td
                            class="py-4 pl-3 pr-4 text-right text-sm font-medium"
                        >
                            <a href="#" class="btn btn-link btn-sm"
                                >Edit<span class="sr-only"
                                    >, {{ person.name }}</span
                                ></a
                            >
                        </td>
                    </tr>
                </template>
            </TableMain>

            <div
                class="flex flex-col gap-2 sm:gap-0 sm:flex-1 sm:flex-row items-center justify-center sm:justify-between pb-10"
            >
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        {{ " " }}
                        <span class="font-medium">1</span>
                        {{ " " }}
                        to
                        {{ " " }}
                        <span class="font-medium">10</span>
                        {{ " " }}
                        of
                        {{ " " }}
                        <span class="font-medium">97</span>
                        {{ " " }}
                        results
                    </p>
                </div>
                <div>
                    <nav
                        class="isolate inline-flex -space-x-px"
                        aria-label="Pagination"
                    >
                        <div
                            class="btn-group border border-gray-200 rounded-md shadow-sm"
                        >
                            <button
                                class="btn btn-link hover:bg-gray-100 disabled:bg-white"
                                :disabled="true"
                            >
                                <ArrowLeftIcon class="h-3 w-3" />
                            </button>
                            <button
                                class="btn btn-link hover:bg-gray-100 disabled:bg-white"
                            >
                                1
                            </button>
                            <button
                                class="hidden sm:inline-flex btn btn-link hover:bg-gray-100 disabled:bg-white"
                            >
                                2
                            </button>
                            <button
                                class="btn btn-link hover:bg-gray-100 disabled:bg-white"
                            >
                                ...
                            </button>
                            <button
                                class="hidden sm:inline-flex btn btn-link hover:bg-gray-100 disabled:bg-white"
                            >
                                99
                            </button>
                            <button
                                class="btn btn-link hover:bg-gray-100 disabled:bg-white"
                            >
                                100
                            </button>
                            <button
                                class="btn btn-link hover:bg-gray-100 disabled:bg-white"
                                :disabled="true"
                            >
                                <ArrowRightIcon class="h-3 w-3" />
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
            <TableStickyFooter
                :selected-items="selectedPeople"
                :show-edit-delete-btn="showEditDeleteBtn"
                :add-new-url="addNewUrl"
                @button-clicked="onToolbarBtnClicked"
            />
        </TransitionRoot>
    </EmployeesWrapper>
</template>
