<script setup>
import {defineAsyncComponent, onMounted, onUnmounted, ref} from "vue";
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode, Pagination} from "swiper/modules";

const modules = [Pagination, FreeMode];

import axios from "axios";

const SectionCard = defineAsyncComponent(() => import('@/Components/SectionCard.vue'));
const PrimaryCard = defineAsyncComponent(() => import('@/Components/PrimaryCard.vue'));


const videos = ref([]);

onMounted(() => {
    axios.get(route('home.recommended-video-list')).then(response => {
        videos.value = response.data.videos;
    })
})


// changing margin respectively the screen size

const gap = ref(1)

// Function to handle screen resize
const updateSlidesPerView = () => {
  gap.value = window.innerWidth < 768 ? 12 : 25;
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
    <SectionCard title="Recommended for you" :has-link="route('videos')">
        <!--        swiper-->
        <div class="">
            <swiper
                :free-mode="true"
                :slidesPerView="'auto'"
                :space-between="gap"
                :modules="modules"
                class="mySwiper"
            >
                <swiper-slide v-for="(item, key) in videos" :key class="max-home-series">
                    <primary-card :item/>
                </swiper-slide>
            </swiper>
        </div>
    </SectionCard>
</template>

<style scoped>
@media (max-width: 768px) {
    .swiper-slide {
        width: fit-content;
    }
}
</style>
