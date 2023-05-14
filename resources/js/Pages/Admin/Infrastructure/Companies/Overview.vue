<script setup>
import CompaniesWrapper from "@/Pages/Admin/Infrastructure/Companies/CompaniesWrapper.vue";
import { TransitionRoot } from "@headlessui/vue";
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import TableMain from "../../../../Components/AdminPages/Overview/TableMain.vue";
import TableStickyFooter from "../../../../Components/AdminPages/Overview/TableStickyFooter.vue";
import TableToolbar from "../../../../Components/AdminPages/Overview/TableToolbar.vue";

const addNewUrl = "admin/infrastructure/companies/add";
const selectedCompanies = ref([]);
const showEditDeleteBtn = computed(() => selectedCompanies.value.length > 0);
const onToolbarBtnClicked = (event) => {
    console.log("tableToolbarBtnClicked", event);
};
</script>

<template>
    <CompaniesWrapper>
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
                :selected-items="selectedCompanies"
                :show-edit-delete-btn="showEditDeleteBtn"
                :add-new-url="addNewUrl"
                @button-clicked="onToolbarBtnClicked"
            />
            <TableMain>
                <template #thead>
                    <tr>
                        <th>Test</th>
                    </tr>
                </template>
                <template #tbody>
                    <tr>
                        <td>Test</td>
                    </tr>
                </template>
            </TableMain>
            <TableStickyFooter
                :selected-items="selectedCompanies"
                :show-edit-delete-btn="showEditDeleteBtn"
                :add-new-url="addNewUrl"
                @button-clicked="onToolbarBtnClicked"
            />
        </TransitionRoot>
    </CompaniesWrapper>
</template>
