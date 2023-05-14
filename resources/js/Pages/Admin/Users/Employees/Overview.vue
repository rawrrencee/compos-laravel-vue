<script setup>
import EmployeesWrapper from "@/Pages/Admin/Users/Employees/EmployeesWrapper.vue";
import {
    EyeIcon,
    PencilIcon,
    UserPlusIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import TableMain from "../../../../Components/AdminPages/Overview/TableMain.vue";
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
        <div class="h-full flex flex-col">
            <TableToolbar
                :selected-items="selectedPeople"
                add-new-url="admin/users/employees/add"
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
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
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
                        <td class="px-3 py-4 text-sm text-gray-500">
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
            <div class="bottom-0 sticky py-4 px-4 sm:hidden">
                <div class="grid grid-cols-2 gap-2" v-if="showEditDeleteBtn">
                    <button type="button" class="btn btn-block btn-primary">
                        <div class="flex gap-2 items-center">
                            <PencilIcon class="w-5 h-5" />
                            <span>Edit ({{ selectedPeople.length }})</span>
                        </div>
                    </button>
                    <button type="button" class="btn btn-block btn-error">
                        <div class="flex gap-2 items-center">
                            <XMarkIcon class="w-5 h-5" />
                            <span>Delete ({{ selectedPeople.length }})</span>
                        </div>
                    </button>
                </div>
                <Link
                    as="button"
                    :href="route('admin/users/employees/add')"
                    class="btn btn-block"
                    v-else
                >
                    <div class="flex gap-2 items-center">
                        <UserPlusIcon class="w-5 h-5" />
                        <span>Add User</span>
                    </div>
                </Link>
            </div>
        </div>
    </EmployeesWrapper>
</template>
