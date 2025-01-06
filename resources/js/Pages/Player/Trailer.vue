<script setup>
import {Head, Link} from '@inertiajs/vue3';
import ProfileImg from '@/images/series-profile.jpg'
import {defineAsyncComponent, ref} from 'vue';
import demoVideo from '@/assets/video/demo-video.mp4'
import Play from "@/Components/svg/icons/Play.vue";
import Tick from "@/Components/svg/icons/tick.vue";
import PlayerVideoCard from "@/Components/PlayerVideoCard.vue";
import VideoPlayerBtn from "@/Components/svg/icons/videoPlayerBtn.vue";
import VideoSidebarCard from "@/Pages/Series/Partials/VideoSidebarCard.vue";

const RumblePlayer = defineAsyncComponent(() => import('@/Components/RumbleVideoPlayer.vue'));
const VideoPlayer = defineAsyncComponent(() => import('@/Components/VideoPlayer.vue'));


defineProps({
    series: Object,
})
</script>

<template>
    <Head title="Trailer"/>
    <div class="max-w-[1860px] mx-auto w-full px-[12px] md:px-[30px]">
        <v-row>
            <v-col cols="12" md="12" lg="8" xl="8" class="relative">
                <VideoPlayer :thumbnail="series.thumbnail" :url_1080="series.trailer_url" :url_720="series.trailer_url" :url_480="series.trailer_url"/>
            </v-col>
            <v-col cols="12" md="12" lg="4" xl="4">
                <div class="bg-series-detail">
                    <div class="flex items-center gap-2.5 profile-order">
                        <v-img cover :src="series.channel?.dp" class="series-profile-img"></v-img>
                        <div>
                            <p class="fs-20 fm-book">{{ series.channel.name }}</p>
                            <p class="fs-16 fm-book text-accent sm-hidden">2 days ago</p>
                        </div>
                    </div>
                    <h1 class="text-52 capitalize mb-0 order-title truncated-heading-3"><span class="text-primaryDark">Trailer:</span> {{ series.title }}
                    </h1>
                    <p class="fs-16 fm-book text-secondary order-title mb-1 md-hidden">2 days ago</p>
                    <p class="fs-20 fw-700  sm-order-1">Total Episodes: {{ series.videos_count }}</p>
                    <p class="fs-14 fw-400  order-title truncated-heading-4 text-wrap">
                        {{ series.description }}</p>

                    <div class="series-btn-custom">
                        <Link :href=" route('series.play',series.id)">

                            <button type="button" class="btn btn-primary w-full">
                            <span>
                                <Play/>
                            </span>
                                Start Series
                            </button>
                        </Link>
                    </div>
                </div>
            </v-col>

        </v-row>
        <!-- ------------------------------------------------ -->
        <v-row class="mt--20 pt-sm-0 pt-md-3">
            <v-col>
                <div class="episodes-div">
                    <PlayerVideoCard
                        v-for="(videos, index) in series.videos" :key="index" :item="videos"
                        :random="false" :user="series.user"
                        :itemsCenter="'align-start '"
                        :orderNumber="index"
                        :widthAuto="'w-full md:w-fit'"
                        :CustomMb="'mb-0'"
                    />
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<style scoped>

</style>
