<script setup>
import {defineAsyncComponent} from "vue";
import {Head} from "@inertiajs/vue3";

const Page = defineAsyncComponent(() => import('@/Components/Page.vue'));
const BannerSlider = defineAsyncComponent(() => import('@/Pages/Home/partials/BannerSlider.vue'));
const Featured = defineAsyncComponent(() => import('@/Pages/Home/partials/Featured.vue'));
const Shorts = defineAsyncComponent(() => import('@/Pages/Home/partials/HomeShorts.vue'));
const Recommended = defineAsyncComponent(() => import('@/Pages/Home/partials/Recommended.vue'));
const Series = defineAsyncComponent(() => import('@/Pages/Home/partials/HomeSeries.vue'));

</script>

<template>
    <Head title="Home"/>

    <!--    Banner swiper -->
    <div class="mx-auto px-[13px] lg:px-[74px] relative w-full md:px-10 max-w-[1928px] md:overflow-hidden">
        <div class="rounded-[30px] overflow-hidden">
            <BannerSlider/>
        </div>
    </div>

    <Page class="flex flex-col w-full gap-[25px] md:gap-y-[40px] lg:gap-y-[72px] mt--91">
        <Featured/>
        <Shorts />
        <Recommended/>
        <Series/>
    </Page>
</template>

<style scoped>
@media (max-width: 768px) {
    .shortsOrder {
        order: 999;
    }
}
</style>

<style>
@media (max-width: 768px) {
    .mySwiper {
        overflow: visible !important;
    }
}
</style>
