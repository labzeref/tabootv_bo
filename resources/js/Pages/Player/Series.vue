<script setup>
import {Head} from '@inertiajs/vue3';

import {defineAsyncComponent, ref} from "vue";
import PlayerVideoCard from "@/Components/PlayerVideoCard.vue";
import Fire from "@/Components/svg/icons/fire.vue";
import Autoplay from "@/Components/svg/icons/autoplay.vue";
import Share from "@/Components/svg/icons/share.vue";
import Trash from "@/Components/svg/icons/trash.vue";
import axios from "axios";
import Comments from "./Partials/Comments.vue";

const RumblePlayer = defineAsyncComponent(() => import('@/Components/RumbleVideoPlayer.vue'));
const VideoPlayer = defineAsyncComponent(() => import('@/Components/VideoPlayer.vue'));

const props = defineProps({
    series: Object,
    currentVideo: Object,
    videosCount: Number,
})
const videos = props.series.videos;


const like = ref(props.currentVideo?.auth_user_liked);
const totalLikes = ref(props.currentVideo.likes);
const totalDislikes = ref(props.currentVideo.dislikes);
const dislike = ref(props.currentVideo?.auth_user_disliked);

const toggleLike = () => {
    if (dislike.value) {
        dislike.value = false
        totalDislikes.value--
    }
    if (like.value) {
        totalLikes.value--
    } else {
        totalLikes.value++
    }
    axios.post(route('videos.toggle-like', props.currentVideo.id)).then(response => {
        if (response.data) {
            like.value = !like.value;
        }
    })
}
const toggleDislike = () => {
    if (like.value) {
        like.value = false
        totalLikes.value--
    }
    if (dislike.value) {
        totalDislikes.value--
    } else {
        totalDislikes.value++
    }

    axios.post(route('videos.toggle-dislike', props.currentVideo.id)).then(response => {
        if (response.data) {
            dislike.value = !dislike.value;
        }
    })
}
console.log('currentVideo', currentVideo)

</script>

<template>
    <Head title="Series Player"/>
    <div class="max-w-[1860px] mx-auto w-full px-[12px] md:px-[30px]">
        <v-row align="start">
            <v-col cols="12" md="12" lg="8" xl="9">
                <VideoPlayer :thumbnail="currentVideo.thumbnail"  :url_1440="currentVideo.url_1440" :url_1080="currentVideo.url_1080"  :url_720="currentVideo.url_720"   :url_480="currentVideo.url_480"  />

                <div class="">
                    <h2 class=" leading-normal">{{ currentVideo.title }}</h2>

                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-2 my-4">
                        <div class="flex items-center gap-2 md:gap-4">
                            <img :src="series.user?.dp" alt="Channel Image" cover class="comment-profile">
                            <div class="space-y-[2px]">
                                <h3 class="capitalize">{{ currentVideo.channel?.name }}</h3>
                                <p class="text-[16px] text-secondary hidden md:block">{{ currentVideo.created_at }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 md:gap-4 ml-auto">
                            <span class="min-w-fit max-w-[115px] md:max-w-[150px] flex items-center">
                                 <v-btn class="actionButtons" style="font-size: 16px ; border-radius: 30px 0 0 30px;" @click="toggleLike()"
                                        :color="like ? 'var(--primary-dark-color)' : ''">
                                <fire/>
                                {{ totalLikes }}
                                </v-btn>
                                <v-btn class="actionButtons" @click="toggleDislike()"
                                       :color="dislike ? 'var(--primary-dark-color)' : ''"
                                       style="font-size: 16px ; border-radius: 0 30px 30px 0; border-left: 2px solid var(--secondary-color)">
                                <trash/>
                                {{ totalDislikes }}
                                </v-btn>
                            </span>
                            <v-btn class="actionButtons" rounded="pill" style="font-size: 16px">
                                <span class="mt-[2px] pr-1">
                                <share/>
                                </span>
                                share
                            </v-btn>
                            <v-btn class="actionButtons" rounded="pill" style="font-size: 16px">
                                <span class="mt-1">
                                    <autoplay/>
                                </span>
                                Autoplay
                            </v-btn>
                        </div>
                    </div>

                    <p class="text-[16px]">{{
                            currentVideo.description
                        }}</p>
                </div>

                <p class="fs-16 fm-book fw-400 mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                    reprehenderit ... </p>

                <div class="mt-5 bg-comments">
                    <Comments :comments="currentVideo.comments" :videoId="currentVideo.id"/>


                </div>

            </v-col>
            <v-col cols="12" md="12" lg="4" xl="3" class="pl-6">
                <div class="w-full">
                    <h5 class="text-start">Upcoming next</h5>

                    <div class="flex flex-col gap-5 mt-5 w-full">
                        <PlayerVideoCard v-for="(video, index) in series.videos"
                                         :key="index"
                                         :orderNumber="index"
                                         :item="video"
                                         :random="false"
                                         :user="series.user"
                                         :videoCount="videosCount"
                                         :playingVideoId="currentVideo.id"
                                         :customWidth="'w-full max-w-full '"
                        />
                    </div>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<style scoped>
.actionButtons{
    width: fit-content !important;
    font-size: 14px !important;
}

@media (min-width: 768px) {
    .actionButtons{
        font-size: 16px;
    }
}

</style>
