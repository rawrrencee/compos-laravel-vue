<script setup>
import CompaniesWrapper from "@/Pages/Admin/Infrastructure/Companies/CompaniesWrapper.vue";
import { UserCircleIcon } from "@heroicons/vue/24/solid";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    errorMessage: String,
    company: Object | undefined,
});

const companyForm = useForm({
    company_name: props.company?.company_name ?? "",
    active: props.company?.active === false ? false : true,
    address_1: props.company?.address_1 ?? "",
    address_2: props.company?.address_2 ?? "",
    email: props.company?.email ?? "",
    phone_number: props.company?.phone_number ?? "",
    mobile_number: props.company?.mobile_number ?? "",
    website: props.company?.website ?? "",
});

const submit = () => {
    if (!props.company) {
        companyForm.post(route("admin/infrastructure/companies/add.store"));
    } else {
        companyForm
            .transform((data) => ({
                ...data,
                id: props.company.id,
            }))
            .post(route("admin/infrastructure/companies/edit.update"));
    }
};
</script>

<template>
    <CompaniesWrapper>
        <Head :title="`${!!company ? 'Edit' : 'Add New'} Employee`" />
        <form
            class="px-4 pt-4 sm:px-0 flex flex-col h-full"
            @submit.prevent="submit"
        >
            <div
                class="flex-1 grow grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 pb-12"
            >
                <div class="sm:col-span-4">
                    <label
                        for="company-name"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Company Name</label
                    >
                    <div class="mt-2 flex flex-col gap-1">
                        <input
                            id="company-name"
                            type="text"
                            name="company-name"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.company_name"
                            :class="
                                companyForm.errors.company_name
                                    ? 'border-error'
                                    : ''
                            "
                            @input="
                                () => companyForm.clearErrors('company_name')
                            "
                        />
                        <span
                            v-if="companyForm.errors.company_name"
                            class="text-error"
                        >
                            {{ companyForm.errors.company_name }}
                        </span>
                    </div>
                </div>

                <div class="col-span-full">
                    <label
                        for="photo"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Photo</label
                    >
                    <div class="mt-2 flex items-center gap-x-3">
                        <UserCircleIcon
                            class="h-12 w-12 text-gray-300"
                            aria-hidden="true"
                        />
                        <button
                            type="button"
                            class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        >
                            Change
                        </button>
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <label
                        for="active"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Active</label
                    >
                    <div class="mt-2">
                        <input
                            type="checkbox"
                            name="active"
                            class="toggle toggle-primary"
                            v-model="companyForm.active"
                        />
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <label
                        for="address_1"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Address Line 1</label
                    >
                    <div class="mt-2">
                        <input
                            id="address_1"
                            type="text"
                            name="address_1"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.address_1"
                        />
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <label
                        for="address_2"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Address Line 2</label
                    >
                    <div class="mt-2">
                        <input
                            id="address_2"
                            type="text"
                            name="address_2"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.address_2"
                        />
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <label
                        for="email"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Email</label
                    >
                    <div class="mt-2">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.email"
                        />
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <label
                        for="phone-number"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Phone Number</label
                    >
                    <div class="mt-2">
                        <input
                            id="phone-number"
                            type="number"
                            name="phone-number"
                            class="input input-bordered w-full max-w-lg [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            v-model="companyForm.phone_number"
                        />
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <label
                        for="mobile-number"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Mobile Number</label
                    >
                    <div class="mt-2">
                        <input
                            id="mobile-number"
                            type="number"
                            name="mobile-number"
                            class="input input-bordered w-full max-w-lg [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            v-model="companyForm.mobile_number"
                        />
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <label
                        for="website"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Website URL</label
                    >
                    <div class="mt-2">
                        <input
                            id="website"
                            type="text"
                            name="website"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.website"
                        />
                    </div>
                </div>
            </div>

            <div class="py-4 bg-white border-t-2 border-gray-100">
                <div
                    class="grid grid-cols-2 gap-2 sm:flex sm:justify-end sm:items-stretch"
                >
                    <Link
                        type="button"
                        class="btn sm:grow sm:basis-0 sm:flex-1 sm:max-w-[10rem] sm:min-w-fit"
                        :href="route('admin/infrastructure/companies')"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="btn btn-primary sm:grow sm:basis-0 sm:flex-1 sm:max-w-[10rem] sm:min-w-fit"
                    >
                        Save
                    </button>
                </div>
            </div>
        </form>
    </CompaniesWrapper>
</template>
