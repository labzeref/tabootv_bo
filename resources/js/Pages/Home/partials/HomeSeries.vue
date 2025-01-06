<script setup>
import SectionCard from "@/Components/SectionCard.vue";
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode, Pagination} from "swiper/modules";
import {defineAsyncComponent, onMounted, onUnmounted, ref} from "vue";

import axios from "axios";

const modules = [Pagination, FreeMode];

const PrimaryCard = defineAsyncComponent(() => import('@/Components/PrimaryCard.vue'));

const series = ref([]);

onMounted(() => {
    axios.get(route('home.series-list')).then(response => {
        series.value = response.data.series;
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
    <SectionCard title="Series"  :has-link="route('series.index')">
        <!--        swiper-->
        <div class="">
            <swiper
                :free-mode="true"
                :slidesPerView="'auto'"
                :space-between="gap"
                :modules="modules"
                class="mySwiper"
            >
                <swiper-slide v-for="(item, key) in series" :key
                              class="max-home-series relative overflow-visible">
                    <primary-card :item="item"/>
                </swiper-slide>
            </swiper>
        </div>
    </SectionCard>
</template>

<style scoped>
@media (min-width: 1600px) {
    .swiper-slide {
        height: 450px;
    }
}
@media (max-width: 1599px) {
    .swiper-slide {
        height: 400px;
    }
}
@media (max-width: 768px) {
    .swiper-slide {
        height: 320px;
        width: fit-content;
    }
}
@media (max-width: 450px) {
    .swiper-slide {
        height: 250px;
        width: fit-content;
    }
}
</style>
