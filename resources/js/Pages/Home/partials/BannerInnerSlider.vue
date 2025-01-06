<script setup>
import {Swiper, SwiperSlide} from 'swiper/vue';
import {Link} from "@inertiajs/vue3"

// Import Swiper styles
import {Pagination} from 'swiper/modules';

import {ref, defineAsyncComponent} from "vue";

const ArrowLeft = defineAsyncComponent(() => import('@/Components/svg/icons/arrowLeft.vue'));
const ArrowRight = defineAsyncComponent(() => import('@/Components/svg/icons/arrowRight.vue'));

defineProps({
    videos: Array
})

// Swiper modules
const modules = [Pagination];

const swiperRef = ref(null);

const init = (swiper) => {
    swiperRef.value = swiper;
}
</script>

<template>
    <div class="max-w-[600px] w-full relative">
        <swiper
            @swiper="init"
            :loop="true"
            :slidesPerView="4"
            :initial-slide="4"
            :space-between="12"
            :slideToClickedSlide="false"
            :centeredSlides="false"
            :modules="modules"
            class="mySwiper"
        >
            <swiper-slide v-for="(video, key) in videos" v-slot="{ isActive }">
                <Link :href="route('series.trailer',video.series_id)">
                    <img :src="video.featured_thumbnail" :key alt="" :class="isActive ? 'bg-white' : ''" class="p-1 rounded-md cursor-pointer" >
                </Link>

            </swiper-slide>
        </swiper>
    </div>

    <button @click="swiperRef.slidePrev()"
        class="absolute -left-4 top-[35%] bottom-[50%] z-10 bg-white size-8 rounded-full flex justify-center items-center"
    ><ArrowLeft /></button>
    <button @click="swiperRef.slideNext()"
        class="absolute -right-4 top-[35%] bottom-[50%] z-10 bg-white size-8 rounded-full flex justify-center items-center"
    ><ArrowRight /></button>
</template>

<style>
</style>
