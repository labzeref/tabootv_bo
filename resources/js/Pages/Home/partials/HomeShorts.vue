<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode, Pagination} from "swiper/modules";
import axios from "axios";

import {defineAsyncComponent, onMounted, onUnmounted, ref} from "vue";
import {Link} from "@inertiajs/vue3";

const SectionCard = defineAsyncComponent(() => import('@/Components/SectionCard.vue'));
const modules = [Pagination, FreeMode];

const videos = ref([]);

onMounted(() => {
    axios.get(route('home.short-videos-list')).then(response => {
        videos.value = response.data.videos;
    });
});


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
    <SectionCard :title="'Shorts'" :has-link="route('shorts.page')">
        <!--        swiper-->
        <div class="">
            <swiper
                :free-mode="true"
                :slidesPerView="'auto'"
                :space-between="gap"
                :modules="modules"
                class="mySwiper"
            >

                <swiper-slide v-for="(video, key) in videos" :key
                              class=" rounded-[6px] max-w-[169px] md:max-w-[190px] min-h-[265px] md:min-h-[305px] w-full relative overflow-hidden cursor-pointer">

                    <Link :href="route('shorts.page', video.uuid)">
                        <div class="relative">
                            <img :src="video.thumbnail" alt="placeholder shorts image"
                                 class="w-full h-[265px] md:h-[305px]">
                            <div class="absolute z-20 left-[10px] bottom-[6px]">
                                <p class="font-medium text-[20px] truncated-heading-2 pr-2" style="line-height: 30px !important">{{ video.title }}</p>
                                <!-- <p class="md:text-sm text-[12px]">{{ video.humans_publish_at }}</p> -->
                            </div>
                        </div>
                    </Link>

                    <div
                        class="z-10 absolute -bottom-1 md:-bottom-10 -left-2 -right-2 top-[50%] md:top-[60%] bg-gradient-to-t from-dark to-dark/0"></div>
                </swiper-slide>
            </swiper>
        </div>
    </SectionCard>
</template>

<style scoped>

</style>
