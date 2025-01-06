<script setup>
import {defineAsyncComponent, ref} from "vue";
import Logo from "@/Components/svg/Logo.vue";
import {useForm} from "@inertiajs/vue3";

const FormCard = defineAsyncComponent(() => import("@/Components/FormCard.vue"))
const TextInput = defineAsyncComponent(() => import("@/Components/TextInput.vue"))

const successMsg = ref(false)

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email')).then(() => {
        successMsg.value = true
    });
};

</script>

<template>
    <FormCard>
        <form @submit.prevent="submit" class="w-[485px] flex flex-col items-center">
            <h2 class="text-center text-[28px] font-bold mb-[10px] h2-small">Reset Your Password</h2>
            <p class="text-center text-[14px] text-secondary mb-3 md:mb-6">Get your reset password link</p>

            <TextInput name="email" label="Email" inputType="email"
                       placeholder="e-mail@example.com" v-model="form.email" :errorMessage="form.errors.email"/>
            <p v-if="successMsg" class="fs-14 fw-400 fm-book text-green-600 w-full mt-[8px]">Email sent successfully!</p>
            <v-btn color="primaryDark" type="submit" rounded="pill" class="w-full rounded-full text-[14px] font-bold py-3 my-6">
                Reset Password
            </v-btn>

            <logo/>
        </form>
    </FormCard>
</template>
