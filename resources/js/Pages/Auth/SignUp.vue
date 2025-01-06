<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed, defineAsyncComponent} from "vue";
import InputError from "@/Components/InputError.vue";
import Logo from "@/Components/svg/Logo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import AuthLogo from "@/Components/logo/AuthLogo.vue";

const SignWithGoogleBtn = defineAsyncComponent(() => import("@/Components/SignWithGoogleBtn.vue"))
const FormCard = defineAsyncComponent(() => import("../../Components/FormCard.vue"))
const TextInput = defineAsyncComponent(() => import("@/Components/TextInput.vue"))
const checkBox = defineAsyncComponent(() => import("@/Components/Checkbox.vue"))

const props = defineProps({
    referralCode: String,
});

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    referral_code: props.referralCode ?? '',
    remember: false,
    privacy_policy: false,
    terms_and_condition: false,
});

const btnDisabled = computed(() => {
    return form.processing || !form.privacy_policy || !form.terms_and_condition;
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Sign-up"/>

    <FormCard>
        <form @submit.prevent="submit" class="w-[485px] flex flex-col items-center">
            <h2 class="text-center text-[28px] font-bold mb-[10px] leading-[26px] fm-bold h2-small ">Sign up</h2>
            <p class="text-center text-[14px] text-secondary mb-6 leading-[13px] fm-book">Welcome to taboo tv</p>

<!--            <SignWithGoogleBtn>Sign up with Google</SignWithGoogleBtn>-->

            <div class="my-8 flex justify-between items-center gap-[17px] w-full">
                <hr class="border border-white/10 w-full">
                <span class="text-[14px] text-secondary min-w-fit leading-[13px] fm-book">Sign up with Email</span>
                <hr class="border border-white/10 w-full">
            </div>
            <div class="w-full space-y-4 mb-4">
                <TextInput name="email" label="Email Address" inputType="email"
                           placeholder="e-mail@example.com" v-model="form.email" :errorMessage="form.errors.email"/>
                <TextInput name="password" label="Password" inputType="password"
                           placeholder="create password" v-model="form.password" :errorMessage="form.errors.password"/>
                <TextInput name="password_confirmation" label="Confirm Password" inputType="password"
                           placeholder="re-enter password" v-model="form.password_confirmation"
                           :errorMessage="form.errors.password_confirmation"/>
            </div>
            <label for="remember-me" class="cursor-pointer mt-1 mb-4">
                <checkbox id="remember-me" name="remember-me" v-model="form.remember"/>
                <span class="ml-[6px] text-[12px] fm-book">Remember me</span>
            </label>

            <!-- <TextInput name="referral_code" label="Referral Code" inputType="text" :required="false"
                       placeholder="Add referral code here (Optional)" v-model="form.referral_code" :errorMessage="form.errors.referral_code"/> -->

            <div class="w-full mt-2 flex flex-col">
                <label for="remember-me" class="cursor-pointer mt-1 mb-2">
                    <checkbox id="remember-me" name="remember-me" v-model="form.privacy_policy"/>
                    <a href="https://taboo.tv/privacy-policy" target="_blank" class="ml-[6px] text-[12px] fm-book ">I have read and agree to the <span class="underline">privacy policy</span></a>
                </label>

                <label for="remember-me" class="cursor-pointer mt-1">
                    <checkbox id="remember-me" name="remember-me" v-model="form.terms_and_condition"/>
                    <a href="https://taboo.tv/terms-&-conditions" target="_blank" class="ml-[6px] text-[12px] fm-book">I have read and agree to the <span class="underline">terms and conditions</span></a>
                </label>
            </div>

            <v-btn color="primaryDark" rounded="pill" type="submit" class="w-full rounded-full text-[14px] py-3 mt-6"
                   min-height="48px" style="line-height: 24px; margin-bottom: 25px; font-weight: 700 " :disabled="btnDisabled">
                Sign up
            </v-btn>

            <span class="text-[14px] leading-[24px] text-center mb-[45px] fm-book">Already have account?
                <Link :href="route('login')" class="text-primary">Login</Link>
            </span>

            <auth-logo/>
        </form>
    </FormCard>


</template>
<style scoped>
.radialBackground {
    background: radial-gradient(93.6% 93.6% at -27.96% 113%, rgba(171, 0, 19, 0.31) 0%, rgba(167, 0, 11, 0) 100%);
    pointer-events: none;
}

.forgot-password {
    color: var(--primary-color);
    display: inline-block;
    border-bottom: 1px solid var(--primary-color);
}


</style>
