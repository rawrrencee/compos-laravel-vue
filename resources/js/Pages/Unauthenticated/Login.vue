<script setup>
import { ExclamationTriangleIcon, LockClosedIcon } from '@heroicons/vue/24/outline';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  companyName: String,
});

const form = useForm({
  email: '',
  password: '',
});

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      remember: form.remember ? 'on' : '',
    }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
      replace: true,
    });
};
</script>

<template>
  <main class="relative h-full bg-gray-800">
    <Head title="Login" />
    <div class="grid h-full grid-cols-1 md:grid-cols-12">
      <div class="col-span-5 hidden h-full flex-col text-gray-200 md:flex">
        <div class="flex h-full flex-col place-content-center place-items-center gap-24 px-6">
          <div class="flex flex-col gap-4">
            <div>
              <img
                class="h-10 w-auto"
                src="https://tailwindui.com/img/logos/mark.svg?color=gray&shade=200"
                alt="Your Company"
              />
            </div>
            <p class="text-2xl font-light">Company Point-of-Sales</p>
            <p class="text-4xl font-black">{{ companyName ?? 'The Organisation' }}</p>
          </div>
        </div>

        <div class="flex flex-row items-center gap-4 px-4 pb-4 text-lg">
          <ExclamationTriangleIcon class="w-h-20 h-20" />
          <small
            >Unauthorised use is strictly prohibited. If you are not authorised, please exit from the System
            immediately. The use of this system is subject to the Computer Misuse Act.</small
          >
        </div>
      </div>
      <div class="col-span-1 h-full rounded-lg bg-gray-200 md:col-span-7">
        <div class="flex h-full flex-col place-content-center place-items-center gap-12 px-6 py-12 md:gap-24">
          <div class="max-w-sm md:hidden md:w-96">
            <div class="flex flex-col gap-4">
              <div>
                <img
                  class="h-10 w-auto"
                  src="https://tailwindui.com/img/logos/mark.svg?color=gray&shade=900"
                  alt="Your Company"
                />
              </div>
              <p class="text-2xl font-light">Company Point-of-Sales</p>
              <p class="text-4xl font-black">{{ companyName ?? 'The Organisation' }}</p>
            </div>
          </div>
          <div class="mx-auto flex w-full max-w-sm flex-col gap-6 md:w-96">
            <div class="flex flex-col gap-4 md:gap-2">
              <div
                class="flex flex-row items-center gap-4 text-sm font-bold tracking-tight text-gray-900 md:text-xl md:leading-9"
              >
                <LockClosedIcon class="h-4 w-4 md:h-6 md:w-6" />

                <p>Please login with your credentials.</p>
              </div>
              <div
                class="flex flex-col items-start gap-1 text-xs text-gray-500 md:flex-row md:items-center md:gap-2 md:leading-6"
              >
                <p>New employee?</p>
                <Link :href="route('unauth/register/employee')" class="underline hover:text-gray-900"
                  >Register here with your organisation key.</Link
                >
              </div>
            </div>

            <form @submit.prevent="submit" class="flex flex-col gap-9">
              <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                  <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                  <div class="flex flex-col gap-1">
                    <input
                      id="username"
                      name="username"
                      type="username"
                      class="input input-bordered w-full"
                      :class="form.errors.email ? 'border-error' : ''"
                      v-model="form.email"
                    />
                    <span v-if="form.errors.email" class="text-error">
                      {{ form.errors.email }}
                    </span>
                  </div>
                </div>

                <div class="flex flex-col gap-1">
                  <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                  <div class="flex flex-col gap-1">
                    <input
                      id="password"
                      name="password"
                      type="password"
                      autocomplete="current-password"
                      class="input input-bordered w-full"
                      :class="form.errors.password ? 'border-error' : ''"
                      v-model="form.password"
                    />
                    <span v-if="form.errors.password" class="text-error">
                      {{ form.errors.password }}
                    </span>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary" :disabled="form.processing">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
