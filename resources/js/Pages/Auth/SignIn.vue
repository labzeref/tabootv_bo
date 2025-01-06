<script setup>
import {defineAsyncComponent} from "vue";
import {Link, useForm, Head} from "@inertiajs/vue3"
import Logo from "@/Components/svg/Logo.vue";
import AuthLogo from "@/Components/logo/AuthLogo.vue";

const FormCard = defineAsyncComponent(() => import("@/Components/FormCard.vue"))
const SignWithGoogleBtn = defineAsyncComponent(() => import("@/Components/SignWithGoogleBtn.vue"))
const TextInput = defineAsyncComponent(() => import("@/Components/TextInput.vue"))
const CheckBox = defineAsyncComponent(() => import("@/Components/Checkbox.vue"))

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login.store'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Sign-in"/>

    <FormCard class="min-h-[845px]">
        <form @submit.prevent="submit" class="w-[485px] flex flex-col items-center">
            <div class="space-y-[10px] mb-6">
                <span class="text-center text-[28px] font-bold leading-[26px] fm-bold">Login to your account</span>
                <p class="text-center text-[14px] text-secondary leading-[13px]">Welcome back! Time to
                    login for continue.</p>
            </div>

<!--            <SignWithGoogleBtn>Sign in with Google</SignWithGoogleBtn>-->

            <div class="my-8 flex justify-between items-center gap-[17px] w-full">
                <hr class="border border-white/10 w-full">
                <span class="text-[14px] text-secondary min-w-fit leading-[13px] fm-book">Sign in with Email</span>
                <hr class="border border-white/10 w-full">
            </div>
            <div class="w-full space-y-4">
                <TextInput name="email" label="Email Address" inputType="email"
                           placeholder="e-mail@example.com" v-model="form.email" :errorMessage="form.errors.email"/>

                <TextInput name="password" label="Password" inputType="password"
                           placeholder="enter password" v-model="form.password" :errorMessage="form.errors.password"/>
            </div>
            <div class="w-full flex items-center justify-between text-[12px] mb-6" style="margin-top: 16px !important">
                <label for="remember-me" class="cursor-pointer">
                    <CheckBox id="remember-me" name="remember-me" v-model="form.remember"/>
                    <span class="ml-[6px] leading-[24px] font-normal fm-book">Remember me</span>
                </label>
                <Link :href="route('password.request')" class="forgot-password underline fm-book">Forgot password?</Link>
            </div>

            <v-btn color="primaryDark" rounded="pill" type="submit" height="48px"
                   class="w-full rounded-full text-[14px] py-3 mb-10 fm-bold" style="font-weight: 700; line-height: 24px">
                Login
            </v-btn>

            <span class="text-[14px] leading-[24px] text-center mb-[45px] fm-book">Don't have account?
                <Link :href="route('choose-plan')" class="text-primary">Create an Account</Link>
            </span>
            <auth-logo />
        </form>
    </FormCard>

</template>
<style scoped>
.forgot-password {
    color: var(--primary-color);
    font-size: 12px;
    font-weight: 400;
    line-height: 24px;
    text-decoration-line: underline;
    text-decoration-style: solid;
    text-underline-position: from-font;
    text-decoration-skip-ink: none;
}

input {
    border: 1px solid #D5D8E2
}

input:checked {
    border: 1px solid #D5D8E2;
}

input:hover:checked {
    border: 1px solid #D5D8E2
}
</style>
