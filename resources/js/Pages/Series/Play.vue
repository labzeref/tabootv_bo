 <script setup>
import {Head, Link} from '@inertiajs/vue3';
import {defineAsyncComponent, ref} from "vue";
import PlayerVideoCard from "@/Components/PlayerVideoCard.vue";
import LikeDislikeButtonGroup from "@/Components/Video/LikeDislikeButtonGroup.vue";
import ShareButton from "@/Components/Video/ShareButton.vue";
import AutoplayButton from "@/Components/Video/AutoplayButton.vue";
import LongVideoCommentSection from "@/Components/Video/LongVideoCommentSection.vue";
import VideoSidebarCard from "@/Pages/Series/Partials/VideoSidebarCard.vue";

const VideoPlayer = defineAsyncComponent(() => import('@/Components/VideoPlayer.vue'));

const props = defineProps({
    video: Object,
    videos: Array,
})
</script>

<template>
    <Head title="Series Player"/>
    <div class="max-w-[1860px] mx-auto w-full px-[12px] md:px-[30px]">
        <v-row align="start">
            <v-col cols="12" md="12" lg="8" xl="8">
                <VideoPlayer :thumbnail="video.thumbnail"  :url_1440="video.url_1440" :url_1080="video.url_1080"  :url_720="video.url_720"   :url_480="video.url_480"  />

                <div class="">
                    <h2 class="mt-25  lh-126">{{ video.title }}</h2>
                    <p class="text-[10px] text-secondary  lg--hidden">{{ video.created_at }}</p>
                    <p class="fs-16 fm-book fw-400 my-[6px] lg--hidden">{{ video?.description }}</p>

                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-3 mt-[6px] md:mt-[18px] md:mb-[30px]">
                        <div class="flex items-center gap-2 md:gap-4">
                            <img :src="video?.channel?.dp" alt="Channel Image" cover class="comment-profile img-63">
                            <div class="space-y-[2px]">
                                <h3 class="capitalize lh-24">{{ video?.channel?.name }}</h3>
                                <p class="text-[16px] text-secondary sm--hidden">{{ video.created_at }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 md:gap-4 ">
                            <LikeDislikeButtonGroup :video="video"/>
                            <!-- <ShareButton/> -->
                            <AutoplayButton/>
                        </div>
                    </div>
                </div>
                <p class="fs-16 fm-book fw-400 mt-5 sm--hidden">{{ video?.description }}</p>


                <div class="bg-comments">
                    <LongVideoCommentSection :video/>
                </div>

                <div class="mt-[14px] lg--hidden">
                    <Link href="" >
                        <button type="button" class="btn btn-primary w-full">
                        <span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.0417 9.17313V9.09792L11.9779 9.1378L4.50478 13.8115L4.50472 13.8116C4.36051 13.9021 4.19473 13.9524 4.02453 13.9574C3.85433 13.9623 3.68591 13.9217 3.5367 13.8396C3.38749 13.7576 3.26292 13.6372 3.17588 13.4909C3.08886 13.3445 3.04253 13.1776 3.04167 13.0074V2.9926C3.04253 2.82237 3.08886 2.65546 3.17588 2.50915C3.26292 2.36281 3.38749 2.24239 3.5367 2.16036C3.68591 2.07834 3.85433 2.03769 4.02453 2.04262C4.19473 2.04755 4.36051 2.09789 4.50472 2.18841L4.50478 2.18845L11.9779 6.8622L12.0417 6.90208V6.82688V2.5C12.0417 2.37844 12.09 2.26186 12.1759 2.17591C12.2619 2.08996 12.3784 2.04167 12.5 2.04167C12.6216 2.04167 12.7381 2.08996 12.8241 2.17591C12.91 2.26186 12.9583 2.37844 12.9583 2.5V13.5C12.9583 13.6216 12.91 13.7381 12.8241 13.8241C12.7381 13.91 12.6216 13.9583 12.5 13.9583C12.3784 13.9583 12.2619 13.91 12.1759 13.8241C12.09 13.7381 12.0417 13.6216 12.0417 13.5V9.17313Z" fill="#E8EAED" stroke="#E8EAED" stroke-width="0.0833333"/>
                            </svg>
                        </span>
                        Play Next
                        </button>
                    </Link>
                </div>

            </v-col>
            <v-col cols="12" md="12" lg="4" xl="4" class="pl-21">
                <div class="w-full">
                    <h5 class="fs-32-next text-start capitalize">Upcoming next</h5>

                    <div class="flex flex-col gap-[12px] md:gap-[25px] mt-[12px] md:mt-[25px] w-full">
                        <VideoSidebarCard
                            v-for="(item, index) in videos"
                            :key="index"
                            :orderNumber="index"
                            :video="item"
                            :currentVideoUuid="video.uuid"
                            :channel="video.channel"
                        />
                    </div>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<style scoped>
.actionButtons {
    width: fit-content !important;
    font-size: 14px !important;
}

.bg-comments{
    margin-top: 25px;
}


@media (min-width: 768px) {
    .bg-comments{
        margin-top: 45px !important;
    }
    .actionButtons {
        font-size: 16px;
    }
}

</style>
