<script setup>
import {Head, Link} from "@inertiajs/vue3"
import {computed, defineAsyncComponent, ref, watch} from "vue";
import {formatAsteriskString} from "@/utils.js";
import infinityIcon from '@/assets/infinity-2.svg'

const checked = defineAsyncComponent(() => import("@/Components/svg/icons/checked.vue"))

const props = defineProps({
    plans: Array
})

const currentTab = ref('yearly')

const activePlan = computed(() => {
    return props.plans.find(plan => plan.name === currentTab.value);
})

const lifetimePlan = computed(() => {
    return props.plans.find(plan => plan.name === 'lifetime');
})

</script>

<template>
    <Head title="Choose Plan"/>

    <div class="grid grid-cols-12 gap-7 max-w-[1308px] mx-auto ">
        <div
        class="col-span-12 lg:col-span-4 w-full  pt-[30px] pb-[26px] px-[13px]  md:pt-[37px] md:pb-[53px] md:px-[33px] bg-[#0A070B]/70 rounded-[20px] overflow-hidden flex flex-col justify-between items-center gap-4 h-full lg:min-h-[500px]">
        <div class="w-full space-y-3 md:space-y-[25px]">
           <div class="flex items-center gap-3">
                <h2 class="font-bold text-[28px] h2-xsmall">Lifetime</h2>
                <img :src="infinityIcon" class="infinity-logo" />
           </div>
            <p class="text-[15px] text-secondary tracking-wider">{{ lifetimePlan.description }}</p>
            <ul class="space-y-3 md:space-y-5 text-secondary mx-auto md:relative fm-book text-[15px]">
                <li><span><checked/></span>Access to all features</li>
                <li><span><checked/></span>⁠Exclusive bonus zoom calls with Arab</li>
             </ul>
        </div>

        <div class="w-full">
            <h4 class="text-primary fm-book text-[#FF3A44] pb-3" style=" line-height: 21.78px">Starts at</h4>
            <h1 style="line-height: 60px !important;  " class="price font-book-400 mb-[20px] w-full">${{ Number(lifetimePlan.price) }}</h1>

            <a :href="lifetimePlan.checkout_url">
                <v-btn color="transparent" height="38px"
                       rounded="pill"
                       style="border: 1px solid white; font-size: 15px; font-weight: 400 !important"
                       class="w-full lg:max-w-[134px] fm-book leading-14">Subscribe
                </v-btn>
            </a>
        </div>
    </div>
        <div
            class=" w-full col-span-12 lg:col-span-8   bg-[#0A070B]/70 rounded-[20px] overflow-hidden relative p-5 md:pt-10 md:pb-[43px] md:px-6   gap-4 md:gap-x-4 md:gap-y-0 justify-between ">
<!--            <Link class="logout-profile-link absolute  fm-medium" :href="route('logout')" method="post">-->
<!--                    Logout-->
<!--            </Link>-->
            <div class="absolute inset-0 radialBackground"></div>
            <div class="flex items-center">
               <div class="flex flex-col justify-between items-center min-h-[500px] w-full md:w-fit gap-6">
                <div class="md:min-h-[168px] md:mr-auto space-y-5 md:space-y-10">
                    <div class="tabs  w-full space-y-[10px] max-h-[52px] mt-[24px]">
                        <v-tabs selected-class="bg-primaryDark" slider-color="transparent" density="comfortable" fixed-tabs>
                            <v-tab rounded="pill" text="Yearly" @click="currentTab = 'yearly'" class="fm-book" style="font-size: 15px; font-weight: 400; line-height: 18px; height: 42px !important;"></v-tab>
                            <v-tab rounded="pill" text="Monthly" @click="currentTab = 'monthly'" class="fm-book" style="font-size: 15px; font-weight: 400; line-height: 18px; height: 42px !important"></v-tab>
                        </v-tabs>
                    </div>
                    <h6 class="text-secondary ml-4 leading-snug max-w-[300px] md:w-[300px]" style="line-height: 34px !important"
                        v-html="formatAsteriskString(activePlan.title, 'text-white font-bold')"></h6>
                </div>

                <!-- list  -->
                <ul class="space-y-3 md:hidden md:space-y-5 text-secondary mx-auto md:min-w-[350px] md:relative fm-book text-[15px]">
                    <li v-for="(feature, key) in activePlan.features" :key><span><checked/></span>{{ feature }}</li>
                 </ul>


                <div class="mt-auto w-full md:w-fit md:min-w-[311px] flex flex-col items-center md:items-start">
                    <p class="text-[18px] md:text-lg text-[#FF3A44] mb-3" style="line-height: 17px !important">
                        Per {{ activePlan.name === 'yearly' ? 'Year' : 'Month' }} <span class="font-bold text-white">(3 days free trial)</span>
                    </p>
                    <div class="flex items-center gap-3 mb-[10px]">
                        <h1 class="font-book-400" style="line-height: 60px !important;">{{ activePlan.price }}$</h1>
                        <v-btn v-if="currentTab == 'yearly'" color="rgba(255, 255, 255, 0.05)" :rounded="false" class="rounded-lg button fm-book leading-14" height="38px" width="138px" >Save
                            {{ activePlan.save_percentage }}%
                        </v-btn>
                    </div>

                    <a :href="activePlan.checkout_url">
                        <v-btn rounded="pill" color="primaryDark" height="45px" class="button px-8 w-full md:w-fit fm-bold leading-14">Subscribe</v-btn>
                    </a>
                </div>
               </div>

               <ul class="space-y-3 hidden md:inline md:space-y-5 text-secondary mx-auto md:max-w-[350px] md:relative fm-book text-[15px] pr-3">
                   <li v-for="(feature, key) in activePlan.features" :key><span><checked/></span>{{ feature }}</li>
                </ul>
            </div>




        </div>

    </div>
</template>

<style scoped>
.leading-14 {
    line-height: 14px
}

.radialBackground {
    background: radial-gradient(93.6% 93.6% at -27.96% 113%, rgba(171, 0, 19, 0.31) 0%, rgba(167, 0, 11, 0) 100%);
    pointer-events: none;
}

.button {
    font-weight: bolder;
    font-size: 15px
}

ul {
    font-size: 15px;
    font-weight: 400;
    line-height: 21px;
    letter-spacing: -0.01em;
}

li {
    display: flex;
    column-gap: 12px;
}

@media (max-width: 768px) {
    ul {
        font-size: 13px;
    }

    .price {
        margin-bottom: 12px !important;
    }
}
</style>
