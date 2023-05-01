<script setup>
import AdminNarrowSidebar from "@/Components/AdminLayout/AdminNarrowSidebar.vue";
import AdminSecondaryNavigation from "@/Components/AdminLayout/AdminSecondaryNavigation.vue";
import AdminSidebar from "@/Components/AdminLayout/AdminSidebar.vue";
import AdminStickyTopBar from "@/Components/AdminLayout/AdminStickyTopBar.vue";
import {
Dialog,
DialogPanel,
TransitionChild,
TransitionRoot,
} from "@headlessui/vue";
import {
BanknotesIcon,
BuildingStorefrontIcon,
ChartPieIcon,
ClockIcon,
CurrencyDollarIcon,
DocumentChartBarIcon,
FolderIcon,
HomeIcon,
HomeModernIcon,
TableCellsIcon,
UserGroupIcon,
XMarkIcon,
} from "@heroicons/vue/24/outline";
import { ref, watch } from "vue";

// #region Page Setup
defineProps({
    secondaryNavigation: Array,
});
// #endregion Page Setup

// #region Page Variables
const employeeNavigation = [
    { name: "Dashboard", href: "dashboard", icon: HomeIcon },
    {
        name: "Sales",
        icon: BanknotesIcon,
        href: "sales",
        children: [
            { name: "Point of Sales", href: "sales/pos" },
            { name: "My Transactions", href: "sales/transactions" },
        ],
    },
    {
        name: "Products",
        icon: FolderIcon,
        href: "products",
        children: [
            { name: "Stock Check", href: "products/stockcheck" },
            { name: "Stocktake", href: "products/stocktake" },
            { name: "Delivery Orders", href: "products/delivery-orders" },
        ],
    },
    { name: "Salaries", href: "salaries", icon: CurrencyDollarIcon },
    {
        name: "Timecards",
        href: "timecards",
        icon: ClockIcon,
    },
];

const adminNavigation = [
    {
        name: "Analytics",
        icon: ChartPieIcon,
        href: "admin/analytics",
        children: [
            { name: "Employees", href: "admin/analytics/employees" },
            { name: "Items", href: "admin/analytics/items" },
            { name: "Sales", href: "admin/analytics/sales" },
        ],
    },
    {
        name: "Inventory Logs",
        href: "admin/inventory-logs",
        icon: TableCellsIcon,
    },
    {
        name: "Salary Reports",
        icon: DocumentChartBarIcon,
        href: "admin/salary-reports",
        children: [
            { name: "Monthly", href: "admin/salary-reports/monthly" },
            { name: "Yearly", href: "admin/salary-reports/yearly" },
        ],
    },
    {
        name: "Infrastructure",
        icon: HomeModernIcon,
        href: "admin/infrastructure",
        children: [
            { name: "Companies", href: "admin/infrastructure/companies" },
            { name: "Stores", href: "admin/infrastructure/stores" },
            { name: "Suppliers", href: "admin/infrastructure/suppliers" },
        ],
    },
    {
        name: "Commerce",
        icon: BuildingStorefrontIcon,
        href: "admin/commerce",
        children: [
            { name: "Brands", href: "admin/commerce/brands" },
            { name: "Categories", href: "admin/commerce/categories" },
            { name: "Items", href: "admin/commerce/items" },
            { name: "Item Kits", href: "admin/commerce/item-kits" },
            { name: "Stocktakes", href: "admin/commerce/stocktake" },
            { name: "Transactions", href: "admin/commerce/transactions" },
        ],
    },
    {
        name: "Users",
        icon: UserGroupIcon,
        href: "admin/users",
        children: [
            { name: "Employees", href: "admin/users/employees" },
            { name: "Customers", href: "admin/users/customers" },
        ],
    },
];
// #endregion Page Variables

// #region Ref Variables
const isMobileSidebarOpen = ref(false);
const isDesktopNarrowSidebarShown = ref(
    sessionStorage.getItem("isDesktopNarrowSidebarShown") === "false"
        ? false
        : true
);
const currentRoute = ref(route().current());
// #endregion Ref Variables

// #region Watch
watch(isDesktopNarrowSidebarShown, (isDesktopNarrowSidebarShown) => {
    sessionStorage.setItem(
        "isDesktopNarrowSidebarShown",
        isDesktopNarrowSidebarShown
    );
});
// #endregion Watch
</script>

<template>
    <div>
        <TransitionRoot as="template" :show="isMobileSidebarOpen">
            <Dialog
                as="div"
                class="relative z-50 lg:hidden"
                @close="isMobileSidebarOpen = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-50/80" />
                </TransitionChild>

                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative mr-16 flex w-full max-w-xs flex-1"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div
                                    class="absolute left-full top-0 flex w-16 justify-center pt-5"
                                >
                                    <button
                                        type="button"
                                        class="-m-2.5 p-2.5"
                                        @click="isMobileSidebarOpen = false"
                                    >
                                        <span class="sr-only"
                                            >Close sidebar</span
                                        >
                                        <XMarkIcon
                                            class="h-6 w-6 text-gray-800"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <AdminSidebar
                                :employee-navigation="employeeNavigation"
                                :admin-navigation="adminNavigation"
                            />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <Transition
            as="div"
            enter-active-class="transition ease-in-out duration-150"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in-out duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            :class="[
                isDesktopNarrowSidebarShown ? 'lg:w-20' : 'lg:w-72',
                'hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col',
            ]"
        >
            <AdminSidebar
                :employee-navigation="employeeNavigation"
                :admin-navigation="adminNavigation"
                v-if="!isDesktopNarrowSidebarShown"
            />
            <AdminNarrowSidebar
                :employee-navigation="employeeNavigation"
                :admin-navigation="adminNavigation"
                v-else
            />
        </Transition>

        <div
            :class="[
                isDesktopNarrowSidebarShown ? 'lg:pl-20' : 'lg:pl-72',
                'transition-[padding] ease-in-out duration-150',
            ]"
        >
            <AdminStickyTopBar
                v-model:is-desktop-narrow-sidebar-shown="
                    isDesktopNarrowSidebarShown
                "
                v-model:is-mobile-sidebar-open="isMobileSidebarOpen"
            />

            <header v-if="$slots.header" class="bg-white shadow">
                <div
                    :class="[
                        secondaryNavigation?.length > 0 ? 'pb-4' : 'pb-6',
                        'max-w-7xl pt-6 px-4 sm:px-6 lg:px-8',
                    ]"
                >
                    <slot name="header" />
                </div>
                <!-- Secondary navigation -->
                <AdminSecondaryNavigation
                    v-if="secondaryNavigation?.length > 0"
                    :secondary-navigation="secondaryNavigation"
                    :current-route="currentRoute"
                ></AdminSecondaryNavigation>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
