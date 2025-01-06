<script setup>
import {computed, defineAsyncComponent, onMounted} from "vue";
import placeholder from '/public/images/placeholder.png'
import {Link} from '@inertiajs/vue3'
import {ref} from 'vue';
import {useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import CardSvg from "@/Components/svg/icons/cardSvg.vue";


const Page = defineAsyncComponent(() => import('@/Components/Page.vue'))
const Cross = defineAsyncComponent(() => import('@/Components/svg/icons/cross.vue'))
const TextInput = defineAsyncComponent(() => import('@/Components/TextInput.vue'))
const LinkInput = defineAsyncComponent(() => import('@/Components/LinkInput.vue'))

const user = computed(() => usePage().props.auth.user);

const dpForm = useForm({
    dp: null,
})

const handleDpChange = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        dpForm.dp = file;

        dpForm.post(route('profile.update-dp'));
    }
};


const countries = ref([]);
const genders = ref([]);

const form = useForm({
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    display_name: user.value.display_name,
    gender: user.value.gender,
    country_id: user.value.country_id,
    phone_number: user.value.phone_number,
    email: user.value.email,
    password: 'password',
    subscription: 'Monthly',
});


const handleSubmit = () => {
    form.post(route('profile.update-profile'));
}

const fetchCountries = () => {
    axios.get(route('asset.countries')).then(response => {
        countries.value = response.data.countries;
        if (!form.country_id) {
            form.country_id = response.data.countries[0].id;
        }
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
    <Page>
        <div
            class=" mx-auto w-full max-w-[1331px] min-h-[1077px] p-[14px] md:p-12 md:border border-white/10 md:bg-[#393535A1] rounded-[20px] relative">
            <Link :href="route('home')">
                <Cross class="absolute top-5 md:top-8 right-5 md:right-8"/>
            </Link>

            <div class="w-full mx-auto max-w-[1027px]">
                <h2 class="h2-2xsmall">Profile</h2>

                <div class="flex items-center gap-5 my-6">
                    <img :src="user.medium_dp" alt="placeholder" class="size-[116px] rounded-full">
                    <label for="dp" class="text-primary text-[14px] cursor-pointer font-normal leading-[24px] fm-book">
                        <span>Change photo</span> <br>
                        <small>Max image size should be 5MB</small>
                    </label>
                    <!-- Hidden file input -->
                    <input
                        id="dp"
                        accept="image/*"
                        type="file"
                        @change="handleDpChange"
                        style="display: none"
                    />
                </div>

                <form @submit.prevent="handleSubmit" action="">
                    <div class="w-full flex flex-col md:flex-row items-start lg:gap-x-[57px] gap-5">

                        <div class=" w-full space-y-[20px]">
                            <TextInput :required="false" name="first_name" label="First Name"
                                       placeholder="Type your first name" v-model="form.first_name"
                                       :errorMessage="form.errors.first_name"/>

                            <TextInput :required="false" name="last_name" label="Last Name"
                                       placeholder="Type your last name" v-model="form.last_name"
                                       :errorMessage="form.errors.last_name"/>

                            <TextInput :required="false" name="display_name" label="Display Name" :append-icon="true"
                                       placeholder="Type your display name (20 characters maximum)"
                                       v-model="form.display_name"
                                       :errorMessage="form.errors.display_name"/>

                            <label for="gender" class="flex flex-col gap-3 text-[14px] w-full max-h-[84px]">
                                <span class="font-medium fm-book">Gender</span>

                                <v-select
                                    placeholder="Select"
                                    name="gender"
                                    density="default"
                                    variant="plain"
                                    :items="genders"
                                    item-title="name"
                                    item-value="value"
                                    v-model="form.gender"
                                >
                                </v-select>
                            </label>

                            <!-- <div class="w-full">
                                <LinkInput name="billing" label="Billing"
                                           :url="route('profile.show')" :icon="CardSvg" :margin-bottom="false"
                                           placeholder="0000 0000 0000 0000"/>
                                <Link href="/profile" class="text-primary text-[14px] leading-[24px] fm-book pt-3">Update payment method
                                </Link>
                            </div> -->
                        </div>

                        <div class="space-y-[20px] w-full mt-[3px]">

                            <label for="country"
                                   class="flex flex-col space-y-3 text-[14px] mb-[25px] w-full max-h-[84px]">
                                <span class="font-medium fm-book">Country</span>

                                <v-combobox
                                    name="country"
                                    density="default"
                                    variant="plain"
                                    :auto-select-first="true"
                                    :items="countries"
                                    item-title="name_with_flag"
                                    item-value="id"
                                    v-model="form.country_id"
                                    :returnObject="false"
                                >
                                </v-combobox>
                            </label>

                            <LinkInput name="phone_number" v-model="form.phone_number" label="Contact Number"
                                       placeholder="+123*********"
                                       :url="route('profile.edit-contact')" linkLabel="Change"/>

                            <LinkInput name="email" v-model="form.email" label="Email"
                                       :url="route('profile.edit-email')" linkLabel="Change"/>

                            <LinkInput name="password" v-model="form.password" label="Password"
                                       :url="route('profile.edit-password')" linkLabel="Change Password"
                                       inputType="password"/>

                            <!-- <LinkInput name="subscription" v-model="form.subscription" label="Subscription" :url="'#'"
                                       :linkLabel="form.subscription === 'Monthly' ? 'Switch to Yearly' : 'Switch to Monthly'"/> -->
                        </div>

                    </div>

                    <v-btn color="primaryDark" type="submit" height="50px" class="button mt-[45px] fm-book"
                           rounded="pill">Save Changes
                    </v-btn>
                </form>


<!--                <div class="flex items-end justify-between w-full mt-[50px] md:mt-[102px]">-->
<!--                    <div class="flex flex-col gap-4">-->
<!--                        &lt;!&ndash; <Link method="POST" :href="route('profile.deactivate')" class="button fs-14 text-[#FFBD00] fm-book fw-400 cursor-pointer">Deactivate my account</Link> &ndash;&gt;-->
<!--                        <v-dialog max-width="659">-->
<!--                            <template v-slot:activator="{ props: activatorProps }">-->

<!--                                    <span class="button text-[#39C9D0] fm-book cursor-pointer" v-bind="activatorProps">Delete account</span>-->
<!--                            </template>-->

<!--                            <template v-slot:default="{ isActive }">-->
<!--                                <div class="p-5 md:py-[44px] md:px-[67px] bg-[#0A070B]/70 rounded-[20px] w-full">-->
<!--                                    <h3 class="font-extrabold text-[24px]">Delete account</h3>-->
<!--                                    <p class="text-secondary mt-3 text-[16px] leading-[150%] fm-book fw-400">Are you sure you would like to-->
<!--                                        Delete account? <br><span class="text-primaryDark fw-700 fm-bold ">This action may not be reversed!</span></p>-->

<!--                                    <div class="flex justify-between gap-2 mt-6">-->
<!--                                        <v-btn @click="isActive.value = false" rounded="pill" height="48px"-->
<!--                                               width="50%" max-width="257px"-->
<!--                                               base-color="accent" style="font-size: 18px" class="">-->
<!--                                            cancel-->
<!--                                        </v-btn>-->
<!--                                        <Link class="w-full"  method="DELETE" href="/profile/delete">-->
<!--                                            <v-btn rounded="pill" height="48px" width="100%" max-width="257px"-->
<!--                                                   base-color="primaryDark" style="font-size: 18px" class="">-->
<!--                                                   Delete account-->
<!--                                            </v-btn>-->
<!--                                        </Link>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                            </template>-->
<!--                        </v-dialog>-->
<!--                        &lt;!&ndash; <Link method="DELETE" href="/profile/delete" class="button text-[#39C9D0] fm-book">Delete account</Link> &ndash;&gt;-->
<!--                    </div>-->

<!--                </div>-->


            </div>
        </div>
    </Page>
</template>

<style scoped>
h2 {
    font-size: 24px;
    font-weight: 500;
    line-height: 36px;
}

button {
    font-size: 14px;
    font-weight: 700;
    line-height: 24px;
    letter-spacing: 0.02em;
    width: 100%;
    max-width: 257px;
}

Link {
    font-size: 14px;
    font-weight: 400;
    line-height: 24px;
    letter-spacing: 0.02em;
}
</style>
