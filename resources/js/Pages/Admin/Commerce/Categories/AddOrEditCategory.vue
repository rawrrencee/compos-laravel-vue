<script setup>
import StickyFooter from '@/Components/AdminPages/AddOrEdit/StickyFooter.vue';
import NoResults from '@/Components/AdminPages/NoResults.vue';
import TableMain from '@/Components/AdminPages/Overview/TableMain.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminAlert from '../../../../Components/AdminLayout/AdminAlert.vue';
import CategoryLayout from './CategoryLayout.vue';

// #region Page Variables
const props = defineProps({
  errorMessage: String,
  category: Object | undefined,
});
const inputFields = [
  { key: 'category_name', title: 'Category Name' },
  { key: 'category_code', title: 'Category Code', maxlength: 4 },
  { key: 'description', title: 'Description' },
];
const categoryForm = useForm({
  category_name: props.category?.category_name ?? '',
  category_code: props.category?.category_code ?? '',
  description: props.category?.description ?? '',
  subcategories: props.category?.subcategories ?? [],
});
// #endregion Page Variables

// #region Ref variables
const showFlashError = ref(true);
const subcategoryForms = ref(
  props.category?.subcategories?.map((s) => ({
    id: s.id ?? '',
    category_id: s.category_id ?? '',
    subcategory_name: s.subcategory_name ?? '',
    subcategory_code: s.subcategory_code ?? '',
    description: s.description ?? '',
    is_deleted: false,
  })) ?? []
);
// #endregion Ref variables

// #region Computed variables
const flashError = computed(() => {
  return {
    show: categoryForm.hasErrors && showFlashError.value,
    type: 'default',
    status: 'error',
    message: 'Please complete or correct the required fields.',
  };
});
const nonDeletedSubcategories = computed(() => {
  return subcategoryForms.value.filter((s) => !s.is_deleted);
});
// #endregion Computed variables

// #region Functions
const onAdminAlertButtonClicked = () => {
  showFlashError.value = false;
};
const onSubcategoryAddClicked = () => {
  subcategoryForms.value.push({
    id: '',
    category_id: props.category?.id ?? '',
    subcategory_name: '',
    subcategory_code: '',
    description: '',
    is_deleted: false,
  });
};
const onSubcategoryRemoveClicked = (subcategory) => {
  if (confirm('Are you sure you want to remove this subcategory?')) {
    if (subcategory.id) {
      subcategory.is_deleted = true;
    } else {
      subcategoryForms.value = subcategoryForms.value.filter((s) => s !== subcategory);
    }
  }
};
const submit = () => {
  showFlashError.value = true;
  categoryForm
    .transform((data) => ({
      ...data,
      ...(props.category?.id && { id: props.category.id }),
      category_code: data.category_code?.toLocaleUpperCase(),
      subcategories: subcategoryForms.value?.map((s) => ({
        ...s,
        subcategory_code: s.subcategory_code?.toLocaleUpperCase(),
      })),
    }))
    .post(route(`admin/commerce/categories/${props.category ? 'edit.update' : 'add.store'}`));
};
// #endregion Functions
</script>

<template>
  <CategoryLayout>
    <Head :title="`${!!category ? 'Edit' : 'Add New'} Category`" />
    <AdminAlert :flash="flashError" @button-clicked="onAdminAlertButtonClicked" />
    <form class="sm:px-6 lg:px-8 px-4 pt-4 flex flex-col gap-x-6 gap-y-8 h-full" @submit.prevent="submit">
      <div class="flex-grow flex flex-col gap-x-6 gap-y-8">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-4" v-for="input in inputFields" :key="input.key">
            <template v-if="input.key === 'description'">
              <label :for="input.key" class="block text-sm font-medium leading-6 text-gray-900">{{
                input.title
              }}</label>
              <div class="mt-2 flex flex-col gap-1">
                <textarea
                  class="textarea textarea-bordered textarea-md w-full max-w-lg"
                  v-model="categoryForm.description"
                ></textarea>
              </div>
            </template>
            <template v-else>
              <label :for="input.key" class="block text-sm font-medium leading-6 text-gray-900">{{
                input.title
              }}</label>
              <div class="mt-2 flex flex-col gap-1">
                <input
                  :type="input.type ?? 'text'"
                  :id="input.key"
                  :name="input.key"
                  :maxlength="input.maxlength"
                  class="input input-bordered w-full max-w-lg"
                  v-model="categoryForm[input.key]"
                  :class="[
                    categoryForm.errors[input.key] ? 'border-error' : '',
                    input.key.includes('_code') && 'uppercase',
                  ]"
                  @input="() => categoryForm.clearErrors([input.key])"
                />
                <span v-if="categoryForm.errors[input.key]" class="text-error">
                  {{ categoryForm.errors[input.key] }}
                </span>
              </div>
            </template>
          </div>
        </div>
        <div class="flex flex-col gap-2">
          <span class="block text-sm font-medium leading-6 text-gray-900">Subcategories</span>
          <TableMain>
            <template #thead>
              <tr class="text-sm text-left leading-6">
                <th scope="col" class="px-3 table-cell md:hidden">Subcategory Details</th>
                <th scope="col" class="px-3 hidden md:table-cell">Subcategory Name</th>
                <th scope="col" class="px-3 hidden md:table-cell md:w-40">Subcategory Code</th>
                <th scope="col" class="px-3 hidden md:table-cell">Description</th>
                <th scope="col" class="px-3 hidden md:table-cell"></th>
              </tr>
            </template>
            <template #tbody>
              <tr v-if="nonDeletedSubcategories.length === 0">
                <td colspan="5">
                  <NoResults message="No Records" />
                </td>
              </tr>
              <tr v-for="(subcategory, index) in nonDeletedSubcategories" :key="index">
                <td class="pr-3 pl-3 py-4">
                  <label for="subcategory_name" class="md:hidden text-sm font-medium leading-6 text-gray-900"
                    >Subcategory Name</label
                  >
                  <input
                    id="subcategory_name"
                    name="subcategory_name"
                    type="text"
                    class="input input-sm input-bordered md:input-lg w-full"
                    v-model="subcategory.subcategory_name"
                  />
                  <dl class="md:hidden">
                    <dt class="sr-only">Subcategory Code</dt>
                    <dd class="mt-2">
                      <label for="subcategory_name" class="text-sm font-medium leading-6 text-gray-900"
                        >Subcategory Code</label
                      >
                      <input
                        id="subcategory_code"
                        name="subcategory_code"
                        type="text"
                        class="input input-sm input-bordered w-full"
                        v-model="subcategory.subcategory_code"
                      />
                    </dd>
                    <dt class="sr-only">Description</dt>
                    <dd class="mt-2">
                      <label for="description" class="text-sm font-medium leading-6 text-gray-900">Description</label>
                      <textarea
                        id="description"
                        name="description"
                        class="textarea textarea-bordered w-full"
                        v-model="subcategory.description"
                      />
                    </dd>
                    <dt class="sr-only">Remove button</dt>
                    <dd class="mt-4">
                      <button
                        type="button"
                        class="btn btn-sm btn-error"
                        @click="onSubcategoryRemoveClicked(subcategory)"
                      >
                        Remove
                      </button>
                    </dd>
                  </dl>
                </td>
                <td class="px-3 hidden md:table-cell">
                  <input
                    type="text"
                    class="input input-lg input-bordered w-full uppercase"
                    maxlength="4"
                    v-model="subcategory.subcategory_code"
                  />
                </td>
                <td class="px-3 hidden md:table-cell">
                  <input type="text" class="input input-lg input-bordered w-full" v-model="subcategory.description" />
                </td>
                <td class="px-3 text-center md:text-left hidden md:table-cell">
                  <button type="button" class="btn btn-sm btn-error" @click="onSubcategoryRemoveClicked(subcategory)">
                    Remove
                  </button>
                </td>
              </tr>
            </template>
            <template #footer>
              <div class="mt-2 grid grid-cols-1 sm:grid-cols-6">
                <button type="button" class="btn btn-primary btn-sm sm:col-span-1" @click="onSubcategoryAddClicked">
                  Add
                </button>
              </div>
            </template>
          </TableMain>
        </div>
      </div>

      <StickyFooter back-url="admin/commerce/categories" />
    </form>
  </CategoryLayout>
</template>
