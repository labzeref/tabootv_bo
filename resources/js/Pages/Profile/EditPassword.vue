<script setup>
import {useForm} from "@inertiajs/vue3";
import EditForm from "@/Pages/Profile/Partials/EditForm.vue";

const form = useForm({
    password: '',
    password_confirmation: '',
});


const handleSubmit = function () {
    form.post(route('profile.update-password'))
}
</script>

<template>
    <EditForm @submit="handleSubmit" title="Change Password" btn="Change Password">
                <v-text-field
                    bg-color="rgba(255, 255, 255, 15%)"
                    rounded="lg"
                    variant="plain"
                    density="comfortable"
                    placeholder="Enter New Password"
                    v-model="form.password"
                    name="password"
                    type="password"
                    :errorMessages="form.errors.password"
                />

                <v-text-field
                    bg-color="rgba(255, 255, 255, 15%)"
                    rounded="lg"
                    variant="plain"
                    density="comfortable"
                    placeholder="Confirm Password"
                    v-model="form.password_confirmation"
                    name="password_confirmation"
                    type="password"
                    :errorMessages="form.errors.password_confirmation"
                />

    </EditForm>
</template>

<style scoped>
h2 {
    font-size: 24px;
    font-weight: 500;
    line-height: 36px;
}

</style>
