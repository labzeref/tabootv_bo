<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode} from "swiper/modules";
import {Pagination} from "swiper/modules";
import {Head, Link} from '@inertiajs/vue3';
import {defineAsyncComponent} from "vue";
import Tick from "@/Components/svg/icons/tick.vue";
import Comments from "@/Components/Video/LongVideoCommentSection.vue";
import ShareButton from "@/Components/Video/ShareButton.vue";
import AutoplayButton from "@/Components/Video/AutoplayButton.vue";
import VideoSidebarCard from "@/Pages/Series/Partials/VideoSidebarCard.vue";

const VideoPlayer = defineAsyncComponent(() => import('@/Components/VideoPlayer.vue'));
const VideoRightSidebarCard = defineAsyncComponent(() => import('@/Pages/Videos/Partials/VideoRightSidebarCard.vue'));
const LikeDislikeButtonGroup = defineAsyncComponent(() => import('@/Components/Video/LikeDislikeButtonGroup.vue'));

const props = defineProps({
    video: Object,
    videos: Array,
})

const modules = [Pagination, FreeMode];
</script>

<template>
    <Head title="Video Player"/>
    <div class="max-w-[1860px] mx-auto w-full px-[12px] md:px-[30px]">
        <v-row align="start">
            <v-col cols="12" md="12" lg="8" xl="8">
                <VideoPlayer :thumbnail="video.thumbnail" :url_1440="video.url_1440" :url_1080="video.url_1080"
                             :url_720="video.url_720" :url_480="video.url_480"/>

                <div class="mt-[25px]">
                    <h2 class="capitalize title">{{ video.title }}</h2>
                    <p class="text-[10px] text-[#807E81] leading-[20px] md:hidden">{{
                            video.humans_publish_at
                        }}</p>

                    <p class="leading-[18px] text-[11px] md:hidden ">{{ video.description }}</p>

                    <div
                        class="flex flex-col md:flex-row justify-between md:items-center gap-3 mt-2 mb-[15px] md:mb-5 py-[10px]">
                        <div class="flex items-center gap-1 md:gap-4">
                            <img :src="video.channel?.dp" alt="Channel Image"
                                 class="size-[18px] md:size-[63px] rounded-full">
                            <div class="space-y-[2px]">
                                <h3 class="capitalize flex gap-[2px] items-center">{{ video.channel?.name }} <span
                                    class="md:hidden"><tick color="#AB0013"/></span></h3>
                                <p class="text-[16px] text-[#807E81] leading-[24px] hidden md:inline">
                                    {{ video.humans_publish_at }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 md:gap-4 ml-auto">
                            <LikeDislikeButtonGroup :video/>
                            <!-- <ShareButton/> -->
                            <AutoplayButton/>
                        </div>
                    </div>

                    <p class="leading-[18px] md:leading-[24px] text-[11px] md:text-[16px] hidden md:inline">
                        {{ video.description }}</p>
                </div>

                <div class="bg-comments">
                    <Comments :video="video"/>
                </div>
            </v-col>

            <v-col cols="12" md="12" lg="4" xl="4">
                <div class="w-full">
                    <div class="flex-col gap-5 mt-5 w-full hidden lg:flex">
                        <VideoRightSidebarCard
                            :actionButtons="true"
                            v-for="(item, index) in videos"
                            :key="index"
                            :video="item"
                            :totalCount="videos.length + 1"
                            :currentVideoUuid="video.uuid"
                            :channel="video.channel"
                        />
                    </div>

                    <!--                    horizontal swiper-->
                    <div class="lg:hidden">
                        <swiper
                            :free-mode="true"
                            :slidesPerView="'auto'"
                            :space-between="12"
                            :modules="modules"
                            class="mySwiper"
                        >
                            <swiper-slide v-for="(item, key) in videos" :key class="max-w-[213px]">
                                <Link :href="route('videos.play',item?.uuid)">
                                    <div
                                        class="gradiant-bg min-h-[227px] p-[10px] flex flex-col gap-3 hover:opacity-85 cursor-pointer duration-300 max-w-[213px] relative">
                                        <div class="w-[192px] h-[113px] rounded-[6px]">
                                            <img :src="item.thumbnail" alt="placeholder Image"
                                                 class="w-full rounded-[6px] object-cover aspect-video">
                                        </div>
                                        <div class="w-full">
                                            <p class="truncated-heading-2 text-[13px] font-medium leading-[18px] fm-book h-[36px]">
                                                {{ item.title }}</p>
                                            <p class="text-[10px] text-secondary mb-[6px]">{{ item.created_at }}</p>
                                            <div class="flex items-center gap-1">
                                                <img :src="item.channel?.dp" alt="" class="rounded-full size-[18px]">
                                                <p class="text-[11px] text-[#9F9F9F] flex items-center gap-[2px]">
                                                    {{ item.channel?.name }}
                                                    <tick color="#AB0013"/>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </swiper-slide>
                        </swiper>
                    </div>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<style scoped>
.gradiant-bg {
    background: linear-gradient(0deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 100%);
    border: 1px solid #FFFFFF1A !important;
    border-radius: 12px;
}

.actionButtons {
    width: fit-content !important;
    font-size: 14px !important;
}

.bg-comments {
    margin-top: 25px;
}

.title{
    line-height: 22px !important;
}
@media (min-width: 768px) {
    .title{
        line-height: 48px !important;
    }

    .bg-comments {
        margin-top: 45px !important;
    }

    .actionButtons {
        font-size: 16px;
    }
}
</style>
