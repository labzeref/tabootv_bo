<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import EditForm from "@/Pages/Profile/Partials/EditForm.vue";
import {watch} from "vue";

const user = usePage().props.auth.user;

const form = useForm({
    phone_number: user.phone_number,
});

const validateNumericInput = (event) => {
    form.phone_number = event.target.value.replace(/(?!^\+)[^0-9]/g, '');
}

const handleSubmit = function () {
    form.post(route('profile.update-contact'))
}

watch(() => form.phone_number, (value) => {
    console.log(value)
})
</script>

<template>
    <EditForm @submit="handleSubmit" title="New Contact" btn="Update Contact">
        <vue-tel-input
            class="text-black w-full border-white/10 rounded-[12px] py-1 focus:border-white mb-5"
            style="background-color: rgba(255, 255, 255, 15%) !important;"
            v-model="form.phone_number"
            mode="international"
            type="tel"
            :inputOptions="{ class: 'bg-transparent' } "
            :defaultCountry="'us'"
            :formatOnDisplay="true"
            :enableAreaCodes="true"
        >
        </vue-tel-input>
        <span class="text-primary text-xs">{{ form.errors.phone_number }}</span>
    </EditForm>

</template>

<style scoped>
</style>
