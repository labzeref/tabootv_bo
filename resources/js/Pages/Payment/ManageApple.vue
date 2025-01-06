<script setup>
import {Head, Link} from "@inertiajs/vue3"
import {computed, defineAsyncComponent, ref, watch} from "vue";
import {formatAsteriskString} from "@/utils.js";
import infinityIcon from '@/assets/infinity-2.svg'


</script>

<template>
    <Head title="Manage Subscription"/>

    <div class="grid grid-cols-12 gap-7 max-w-[1308px] mx-auto ">
        <div
            class=" mx-auto  col-span-12 lg:col-span-4 w-full  pt-[30px] pb-[26px] px-[13px]  md:pt-[37px] md:pb-[53px] md:px-[33px] bg-[#0A070B]/70 rounded-[20px] overflow-hidden flex flex-col justify-between items-center gap-4 lg:min-h-[500px]">
            <div class="w-full space-y-3 md:space-y-[25px]">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-[28px] h2-xsmall">Manage Subscription</h2>
                </div>
                <p class="text-[15px] text-secondary tracking-wider">You have purchased your subscription on an iOS device please manage your subscription on you apple</p>
                <a target="_blank" href="https://apps.apple.com/account/subscriptions">
                    <v-btn color="transparent" height="38px"
                           rounded="pill"
                           style="border: 1px solid white; font-size: 15px; font-weight: 400 !important"
                           class="w-full lg:max-w-[134px] fm-book leading-14 mt-5">Manage on apple
                    </v-btn>
                </a>
            </div>
        </div>

    </div>
</template>

<style scoped>

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
