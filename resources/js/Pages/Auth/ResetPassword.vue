<script setup>
/*
import { Head, useForm } from '@inertiajs/vue3';
import {defineAsyncComponent} from "vue";

const GuestLayout = defineAsyncComponent(() => import('@/Layouts/GuestLayout.vue'));
const InputError = defineAsyncComponent(() => import('@/Components/InputError.vue'));
const InputLabel = defineAsyncComponent(() => import('@/Components/InputLabel.vue'));
const PrimaryButton = defineAsyncComponent(() => import('@/Components/PrimaryButton.vue'));
const TextInput = defineAsyncComponent(() => import('@/Components/TextInput.vue'));
*/
import {defineAsyncComponent} from "vue";
import Logo from "@/Components/svg/Logo.vue";
import {Link, useForm} from "@inertiajs/vue3";

const FormCard = defineAsyncComponent(() => import("@/Components/FormCard.vue"))
const TextInput = defineAsyncComponent(() => import("@/Components/TextInput.vue"))


const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <FormCard>
        <form @submit.prevent="submit" class="w-[485px] flex flex-col items-center">
            <h2 class="text-center text-[28px] font-bold mb-[10px] h2-small">Reset Your Password</h2>
            <p class="text-center text-[14px] text-secondary mb-3 md:mb-6">Welcome ! Time to reset your password.</p>

            <div class="w-full space-y-2 md:space-y-4 mb-4">
                <TextInput name="email" label="Email" inputType="email"
                           placeholder="e-mail@example.com" v-model="form.email" :errorMessage="form.errors.email"/>

                <TextInput name="password" label="Password" inputType="password"
                           placeholder="enter password" v-model="form.password" :errorMessage="form.errors.password"/>

                <TextInput name="password_confirmation" label="Confirm Password" inputType="password"
                           placeholder="re-enter password" v-model="form.password_confirmation" :errorMessage="form.errors.password_confirmation"/>
            </div>

            <v-btn color="primaryDark" type="submit" rounded="pill" class="w-full text-[14px] font-bold py-3 mb-4">
                Reset Password
            </v-btn>
<!--            <span class="text-14px text-center mt-5 md:mt-10 mb-6 md:mb-11">Don't have an account ?-->
<!--                <Link :href="route('choose-plan')" class="text-primary">Create Account</Link> -->
<!--            </span>-->

            <logo/>
        </form>
    </FormCard>
</template>
