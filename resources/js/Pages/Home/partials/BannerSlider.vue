<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {Pagination} from "swiper/modules";
import {defineAsyncComponent, onMounted, ref} from "vue";
import axios from "axios";

const Banner = defineAsyncComponent(() => import('@/Pages/Home/partials/Banner.vue'));
const ArrowLeft = defineAsyncComponent(() => import('@/Components/svg/icons/arrowLeft.vue'));
const ArrowRight = defineAsyncComponent(() => import('@/Components/svg/icons/arrowRight.vue'));

const modules = [Pagination];
const swiperRef = ref(null)
const banners = ref([]);

const init = (swiper) => {
    swiperRef.value = swiper;
}

onMounted(() => {
    axios.get(route('home.banners-list')).then(response => {
        banners.value = response.data.banners;
    })
})
</script>

<template>
    <swiper
        @swiper="init"
        :space-between="50"
        :modules="modules"
        class="mySwiper"
        :loop="true"
    >
        <swiper-slide
            v-for="(banner, key) in banners"
            :key
            class="h-auto overflow-visible rounded-[30px]"
        >
            <Banner :banner/>
        </swiper-slide>
    </swiper>

    <!-- <button @click="swiperRef.slidePrev()"
            class="hidden md:flex absolute -left-11 top-[45%] bottom-[50%] z-10 bg-[#282828] rounded-full justify-end px-3 items-center size-[72px]"
    >
        <ArrowLeft/>
    </button>
    <button @click="swiperRef.slideNext()"
            class="hidden md:flex absolute -right-11 top-[45%] bottom-[50%] z-10 bg-primaryDark rounded-full justify-start px-3 items-center size-[72px]"
    >
        <ArrowRight/>
    </button> -->
</template>
