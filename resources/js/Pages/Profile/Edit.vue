<script setup>
import { defineAsyncComponent } from 'vue';
import { Head } from '@inertiajs/vue3';

const AuthenticatedLayout = defineAsyncComponent(() => import('@/Layouts/AuthenticatedLayout.vue'));
const DeleteUserForm = defineAsyncComponent(() => import('./Partials/DeleteUserForm.vue'));
const UpdatePasswordForm = defineAsyncComponent(() => import('./Partials/UpdatePasswordForm.vue'));
const UpdateProfileInformationForm = defineAsyncComponent(() => import('./Partials/UpdateProfileInformationForm.vue'));

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
</template>
