<script setup>
import CompaniesWrapper from "@/Pages/Admin/Infrastructure/Companies/CompaniesWrapper.vue";
import { getImgSrcFromPath } from "@/Util/Photo";
import { PhotoIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

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
    img_url: props.company?.img_url ?? "",
    company_photo: null,
});
const photoPreview = ref(null);
const photoInput = ref(null);
const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (!photo) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};
const submit = () => {
    if (!props.company) {
        companyForm
            .transform((data) => ({
                ...data,
                ...(photoInput.value && {
                    company_photo: photoInput.value.files[0],
                }),
            }))
            .post(route("admin/infrastructure/companies/add.store"));
    } else {
        companyForm
            .transform((data) => ({
                ...data,
                ...(photoInput.value && {
                    company_photo: photoInput.value.files[0],
                }),
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

                <div class="sm:col-span-4">
                    <label
                        for="company_photo"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Company Photo</label
                    >
                    <div
                        class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 max-w-lg"
                    >
                        <div
                            class="text-center flex flex-col gap-2 justify-center"
                        >
                            <div
                                v-if="!photoPreview"
                                class="mt-2 flex flex-col gap-4 items-center"
                            >
                                <img
                                    v-if="company?.img_url || company?.img_path"
                                    :src="
                                        company?.img_path
                                            ? getImgSrcFromPath(
                                                  company?.img_path
                                              )
                                            : company?.img_url
                                    "
                                    class="rounded-lg h-20 w-20 object-cover"
                                />
                                <PhotoIcon
                                    class="mx-auto h-12 w-12 text-gray-300"
                                    aria-hidden="true"
                                    v-else
                                />
                                <button
                                    type="button"
                                    class="btn btn-ghost btn-sm"
                                    v-if="company?.img_path"
                                    :disabled="
                                        !!company?.img_url && !company?.img_path
                                    "
                                    @click="
                                        () => {
                                            router.post(
                                                route(
                                                    'admin/infrastructure/companies/photo.delete'
                                                ),
                                                {
                                                    id: company?.id,
                                                    img_path: company?.img_path,
                                                }
                                            );
                                        }
                                    "
                                >
                                    <div class="flex gap-2 items-center">
                                        <XMarkIcon class="h-3 w-3" />
                                        <span>Remove</span>
                                    </div>
                                </button>
                                <span
                                    class="text-gray-600 text-xs"
                                    v-if="
                                        company?.img_url && !company?.img_path
                                    "
                                    >Image populated from Image URL (filled in
                                    below)</span
                                >
                            </div>
                            <div v-else class="self-center">
                                <span
                                    class="block rounded-lg w-20 h-20 bg-cover bg-no-repeat bg-center"
                                    :style="
                                        'background-image: url(\'' +
                                        photoPreview +
                                        '\');'
                                    "
                                />
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class="flex text-sm leading-6 justify-center"
                                >
                                    <label
                                        for="company_photo"
                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-primary focus-within:outline-none focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 hover:text-secondary"
                                    >
                                        <span
                                            >Click to upload
                                            {{
                                                company?.img_path
                                                    ? "another"
                                                    : "a new"
                                            }}
                                            file</span
                                        >
                                        <input
                                            id="company_photo"
                                            name="company_photo"
                                            type="file"
                                            class="sr-only"
                                            ref="photoInput"
                                            @change="updatePhotoPreview"
                                        />
                                    </label>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">
                                    PNG or JPG up to 10MB
                                </p>
                            </div>
                        </div>
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
                    <div class="mt-2 flex flex-col gap-1">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.email"
                            :class="
                                companyForm.errors.email ? 'border-error' : ''
                            "
                            @input="() => companyForm.clearErrors('email')"
                        />
                        <span
                            v-if="companyForm.errors.email"
                            class="text-error"
                        >
                            {{ companyForm.errors.email }}
                        </span>
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
                <div class="sm:col-span-4">
                    <label
                        for="img_url"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Image URL</label
                    >
                    <div class="mt-2">
                        <input
                            id="img_url"
                            type="text"
                            name="img_url"
                            class="input input-bordered w-full max-w-lg"
                            v-model="companyForm.img_url"
                        />
                    </div>
                </div>
            </div>

            <div
                class="bottom-0 sticky py-4 bg-white border-t-2 border-gray-100"
            >
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
