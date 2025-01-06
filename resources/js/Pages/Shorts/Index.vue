<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {Pagination, Mousewheel} from "swiper/modules";
import {defineAsyncComponent, onMounted, onUnmounted, ref} from "vue";
import shortVideosStore from "@/stores/shortVideosStore.js";
import ArrowDownSvg from "@/Components/svg/icons/arrowDownSvg.vue";
import ArrowUpSvg from "@/Components/svg/icons/arrowUpSvg.vue";

const Page = defineAsyncComponent(() => import("@/Components/Page.vue"))
const ShortVideoCard = defineAsyncComponent(() => import("@/Pages/Shorts/Partials/ShortVideoCard.vue"))

const modules = [Pagination, Mousewheel];

const props = defineProps({
    video: {
        type: [Object, null]
    },
})

const swiperRef = ref(null);

const initSwiper = (swiper) => {
    // console.log('swiper', swiper);
    swiperRef.value = swiper;
}

const updateDisplayVideoIndex = (swiper) => {
    shortVideosStore.updateDisplayVideoIndex(swiper.activeIndex);
}

onMounted(() => {
    if (props.video) {
        shortVideosStore.appendVideo(props.video);
    }

    shortVideosStore.fetchVideos();
});


// changing slides per view respectively the screen size

const slidesPerView = ref(1)

// Function to handle screen resize
const updateSlidesPerView = () => {
  slidesPerView.value = window.innerWidth < 768 ? 1 : 1.18;
};

// Listen for window resize events
onMounted(() => {
  window.addEventListener('resize', updateSlidesPerView);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateSlidesPerView);
});

// Watch the screen size on initial load
updateSlidesPerView();
</script>

<template>
    <div class="flex justify-center w-full">
        <Page class="w-full relative my-auto mx-auto">
        <swiper
            @swiper="initSwiper"
            @slideChange="updateDisplayVideoIndex"
            direction="vertical"
            :slides-per-view="1"
            :modules="modules"
            :mousewheel="false"
            :space-between="45"
            class="mySwiper"
        >
            <swiper-slide v-for="(video, index) in shortVideosStore.list" :key="index">
                <ShortVideoCard :video="video" :index/>
            </swiper-slide>
        </swiper>

        <div class="short-video-navigation-btn flex-col gap-4 absolute top-[40%] right-0">
            <div class="size-[56px] ">
                <v-btn height="56px" width="56px" rounded="pill" @click="swiperRef.slidePrev()" v-if="shortVideosStore.displayVideoIndex > 0" :icon="ArrowUpSvg" />
            </div>
            <v-btn height="56px" width="56px" rounded="pill" @click="swiperRef.slideNext()" :icon="ArrowDownSvg" />
        </div>

    </Page>
    </div>
</template>

<style scoped>
.mySwiper {
    height: 80vh;
    aspect-ratio: 9/16;
    width: 100%;
}

@media (max-width: 990px) {
    .mySwiper {
        height: 90vh;
    }
}

@media (max-width: 500px) {
    .mySwiper {
        height: auto;
        width: 100%;
    }
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
