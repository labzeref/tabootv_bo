<script setup>
import {Link, useForm, Head, usePage} from "@inertiajs/vue3"

import {defineAsyncComponent, ref} from "vue";
import Logo from "@/Components/svg/Logo.vue";
import AuthLogo from "@/Components/logo/AuthLogo.vue";

const FormCard = defineAsyncComponent(() => import("@/Components/FormCard.vue"))
const TextInput = defineAsyncComponent(() => import("@/Components/TextInput.vue"))

const props = defineProps({
    plan: Object,
});

const user = usePage().props.auth.user;

const form = useForm({
    card_name: '',
    email: user.email,
    card_number: '',
    expiry_date: '',
    cvv_cvc: '',
});

const handleSubmit = () => {
    form.post(route('payment.subscribe', props.plan.id));
}

</script>

<template>
    <Head title="Checkout"/>
    <FormCard class="min-h-[845px] grid place-items-center">
        <form @submit.prevent="handleSubmit" class="md:w-[485px] flex flex-col items-center">
            <h2 class="text-center h2-small">Checkout</h2>

            <div class="space-y-3 my-6 w-full">
                <TextInput name="card_name" label="Name On Card"
                           placeholder="Example: John Doe" v-model="form.card_name"
                           :errorMessage="form.errors.card_name"/>

                <TextInput name="email" label="Email"
                           placeholder="Example: JohnDo@email.com" v-model="form.email"
                           :errorMessage="form.errors.email"/>

                <TextInput name="card_number" label="Card Number" :append-icon="true"
                           placeholder="0000 0000 0000 0000" v-model="form.card_number"
                           :errorMessage="form.errors.card_number"/>

                <div class="w-full flex justify-between gap-2 md:gap-4">
                    <TextInput name="expiry_date" label="Expiry Date"
                               inputType="date"
                               placeholder="Example: John Doe" v-model="form.expiry_date"
                               :errorMessage="form.errors.expiry_date"/>

                    <TextInput name="cvv_cvc" label="CVV/CVC" inputType="number"
                               placeholder="0000" v-model="form.cvv_cvc"
                               :errorMessage="form.errors.cvv_cvc"/>
                </div>
            </div>

            <v-btn color="primaryDark" type="submit" rounded="pill" height="48px"
                   class="w-full rounded-full text-[14px] fm-bold py-3 mb-[45px]" style="font-weight: 700;">
                Get Started
            </v-btn>
            <auth-logo />
        </form>
    </FormCard>
</template>

<style>

</style>
