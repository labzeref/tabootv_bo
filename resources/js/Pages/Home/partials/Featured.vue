<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode} from "swiper/modules";
import {Pagination} from "swiper/modules";
import {defineAsyncComponent, onMounted, ref} from "vue";

import axios from "axios";
import {formatDuration} from "../../../utils.js";
import {Link} from "@inertiajs/vue3";

const Tick = defineAsyncComponent(() => import('@/Components/svg/icons/tick.vue'));

const modules = [Pagination, FreeMode];
const videos = ref([]);

onMounted(() => {
    axios.get(route('home.featured-video-list')).then(response => {
        videos.value = response.data.videos;

    });
})
</script>

<template>
    <div class="text-white space-y-3 md:space-y-6 2xl:space-y-10">
        <h2 class=" h2-medium">Featured</h2>
        <!-- <h2 class="md:hidden" style="font-weight: 700 !important; line-height: 20px">Newest Uploads</h2> -->

        <!--        grid-->
        <div class="mx-0 w-full pa-0 hidden lg:grid grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-[25px]">
                        <Link :href="route('videos.play',video.uuid)" v-for="(video, key) in videos" class="max-w-[579px] w-full"
                    :key>
                        <div
                            class="secondaryCard h-[119px] p-[10px] flex flex-col md:flex-row gap-3 hover:opacity-85 cursor-pointer duration-300 w-full" >
                            <div class="md:max-w-[170px] md:h-[99px] w-full relative">
                                <img :src="video.featured_thumbnail" alt="placeholder Image"
                                     class="w-full rounded-[6px] xl:max-w-[170px] xl:min-h-[99px] imageAspectRatio">
                            </div>

                            <div class="w-full py-[6px]">
                                <h3 class="w-full leading-5 truncated-heading">{{ video.title }}</h3>
                                <p class="text-sm text-secondary mb-4">{{ video.humans_publish_at }}</p>
                                <p class="text-sm text-[#9F9F9F] flex items-center gap-1">{{video.channel?.name}}
                                    <tick/>
                                </p>
                            </div>
                        </div>
                        </Link>

        </div>

        <!--        swiper-->
        <div class="lg:hidden">
            <swiper
                :free-mode="true"
                :slidesPerView="'auto'"
                :space-between="12"
                :modules="modules"
                class="mySwiper"
            >
                <swiper-slide v-for="(video, key) in videos" :key class="max-w-[213px]">
                    <div
                        class="primaryCard h-[227px] p-[10px] flex flex-col gap-3 hover:opacity-85 cursor-pointer duration-300 max-w-[213px]">
                        <div class="w-full relative max-w-[192px] max-h-[193px] overflow-hidden rounded-[6px]">
                            <img :src="video.featured_thumbnail" alt="placeholder Image" class="w-full rounded-[6px]">
                        </div>
                        <div class="w-full">
                            <h3 class="truncated-heading-2" style="font-size: 13px !important; font-weight: 500 !important; line-height: 18px !important;">{{ video.title }}</h3>
                            <p class="text-sm text-secondary mb-[6px]">{{ video.humans_publish_at }}</p>
                            <p class="text-[11px] text-[#9F9F9F] flex items-center gap-1">
                                <img :src="video.channel?.dp" alt="dp" class="size-[18px] rounded-full">
                                {{ video.channel?.name }}
                                <tick/>
                            </p>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>
        </div>

    </div>

</template>

<style scoped>
.primaryCard{
    padding: 10px !important;
}
@media (max-width: 768px) {
    .imageAspectRatio {
        aspect-ratio: 3 / 2
    }
}

</style>
