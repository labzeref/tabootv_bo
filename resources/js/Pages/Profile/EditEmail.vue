<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import EditForm from "@/Pages/Profile/Partials/EditForm.vue";

const user = usePage().props.auth.user;

const form = useForm({
    email: user.email,
});


const handleSubmit = function () {
    form.post(route('profile.update-email'))
}
</script>

<template>
    <EditForm @submit="handleSubmit" title="New Email" btn="Update Email">
        <v-text-field
                bg-color="rgba(255, 255, 255, 15%)"
                rounded="lg"
                variant="plain"
                density="comfortable"
                placeholder="Enter New Email Address"
                v-model="form.email"
                name="email"
                type="email"
                :errorMessages="form.errors.email"
            />
    </EditForm>
</template>

<style scoped>
</style>
