<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout
}
</script>

<script setup>
import {Link, useForm, Head, usePage} from "@inertiajs/vue3"

import {defineAsyncComponent, onMounted, ref} from "vue";
import Logo from "@/Components/svg/Logo.vue";
import axios from "axios";
import AuthLogo from "@/Components/logo/AuthLogo.vue";

const FormCard = defineAsyncComponent(() => import("@/Components/FormCard.vue"))
const TextInput = defineAsyncComponent(() => import("@/Components/TextInput.vue"))

const user = usePage().props.auth.user;
const countries = ref([]);
const genders = ref([]);

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    display_name: user.display_name,
    gender: user.gender,
    country_id: user.country_id ?? 236,
    phone_number: user.phone_number,
});

const handleSubmit = () => {
    form.post(route('profile.complete.store'));
}

const fetchCountries = () => {
    axios.get(route('asset.countries')).then(response => {
        countries.value = response.data.countries;
    });
}

const fetchGenders = () => {
    axios.get(route('asset.gender-list')).then(response => {
        genders.value = response.data.genders;
    });
}

const validateNumericInput = (event) => {
    form.phone_number = event.target.value.replace(/(?!^\+)[^0-9]/g, '');
}

onMounted(() => {
    fetchGenders();
    fetchCountries();
})
</script>

<template>
    <Head title="Profile"/>
    <FormCard class="pt-[78px] pb-[76px] min-h-[845px] grid place-items-center">
        <form @submit.prevent="handleSubmit" class=" md:w-[485px] flex flex-col items-center">
            <h2 class="text-center h2-small mb-[10px]">Complete your profile</h2>
            <p class="text-center text-[14px] text-secondary mb-6 leading-[13px] fm-book">You're almost
                there</p>

            <div class="space-y-4 w-full">
                <div class="w-full flex flex-col md:flex-row justify-between gap-2 md:gap-4">
                    <TextInput name="first_name" label="First Name"
                               placeholder="Type your first name" v-model="form.first_name"
                               :errorMessage="form.errors.first_name"/>

                    <TextInput name="last_name" label="Last Name"
                               placeholder="Type your last name" v-model="form.last_name"
                               :errorMessage="form.errors.last_name"/>
                </div>
                <TextInput name="display_name" label="Display Name" :append-icon="true"
                           placeholder="Type your display name (20 characters maximum)" v-model="form.display_name"
                           :errorMessage="form.errors.display_name"/>

                <label for="gender" class="flex flex-col gap-3 text-[14px] w-full">
                    <span class="font-medium fm-book">Gender <span class="text-primary">*</span></span>

                    <v-select
                        placeholder="Select"
                        name="gender"
                        density="default"
                        variant="plain"
                        :items="genders"
                        item-title="name"
                        item-value="value"
                        v-model="form.gender"
                        :error="form.errors.gender"
                        :error-messages="form.errors.gender"
                    >
                    </v-select>
                </label>
            </div>

            <label for="country" class="flex flex-col gap-3 text-[14px] w-full">
                <span class="font-medium fm-book">Country <span class="text-primary">*</span></span>

                <v-combobox
                    name="country_id"
                    density="default"
                    variant="plain"
                    :items="countries"
                    item-title="name_with_flag"
                    item-value="id"
                    v-model="form.country_id"
                    :returnObject="false"
                >
                </v-combobox>
                <span v-if="form.errors.country_id"
                      class="text-red-500 text-xs fm-book">{{ form.errors.country_id }}</span>

            </label>

            <label for="phone_number" class="flex flex-col gap-3 text-[14px] w-full">
                    <span class="font-medium fm-book">Contact Number <span v-if="form.errors.phone_number"
                                                                              class="text-red-500 text-xs ml-5">invalid phone number</span></span>

                <vue-tel-input
                    class="text-black w-full border-white/10 rounded-[12px] py-1 focus:border-white mb-6"
                    v-model="form.phone_number"
                    mode="international"
                    type="tel"
                    :inputOptions="{ class: 'bg-transparent' } "
                    :defaultCountry="'us'"
                    :formatOnDisplay="true"
                    :enableAreaCodes="true"
                >
                </vue-tel-input>

            </label>


            <v-btn color="primaryDark" type="submit" height="48px" rounded="pill"
                   class="w-full text-[14px] py-3 fm-bold" style="font-weight: 700">
                Get Started
            </v-btn>

            <div class="mt-[45px]">
                <auth-logo/>
            </div>
        </form>
    </FormCard>
</template>

<style>

</style>
