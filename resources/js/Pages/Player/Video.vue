<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode} from "swiper/modules";
import {Pagination} from "swiper/modules";

import {Head} from '@inertiajs/vue3';
import ProfileImg from '@/images/series-profile.jpg'
import demoVideo from '@/assets/video/demo-video.mp4'
import {defineAsyncComponent, onMounted, ref} from "vue";
import PlayerVideoCard from "@/Components/PlayerVideoCard.vue";
import Tick from "@/Components/svg/icons/tick.vue";
import axios from "axios";
import VideoPlayerBtn from "@/Components/svg/icons/videoPlayerBtn.vue";
import Fire from "@/Components/svg/icons/fire.vue";
import Trash from "@/Components/svg/icons/trash.vue";
import Share from "@/Components/svg/icons/share.vue";
import Autoplay from "@/Components/svg/icons/autoplay.vue";
import Comments from "./Partials/Comments.vue";

const VideoPlayer = defineAsyncComponent(() => import('@/Components/VideoPlayer.vue'));

const props = defineProps({
    video: Object
})

const modules = [Pagination, FreeMode];
const videos = ref([]);
const totalLikes = ref();
const totalDislikes = ref();
const has_liked = ref();
const dislike = ref();
onMounted(() => {
    videos.value = [props.video]
    totalLikes.value = props.video.likes_count;
    totalDislikes.value = props.video.dislikes_count;
    has_liked.value = props.video.has_liked;
    dislike.value = props.video.has_disliked;


    axios.get(route('home.recommended-video-list')).then(response => {
        console.log(response.data)
        videos.value = response.data.videos;
    })


})
const toggleLike = () => {

    axios.post(route('videos.toggle-like', props.video.id)).then(response => {
        if (response.data) {
            if (dislike.value) {
                dislike.value = false
                totalDislikes.value--
            }
            if (has_liked.value) {
                totalLikes.value--
            } else {
                totalLikes.value++
            }
            has_liked.value = !has_liked.value;
        }
    })
}
const toggleDislike = () => {

    axios.post(route('videos.toggle-dislike', props.video.id)).then(response => {
        if (response.data) {
            if (has_liked.value) {
                has_liked.value = false
                totalLikes.value--
            }
            if (dislike.value) {
                totalDislikes.value--
            } else {
                totalDislikes.value++
            }
            dislike.value = !dislike.value;
        }
    });

}


</script>

<template>
    <Head title="Video Player"/>
    <div class="max-w-[1860px] mx-auto w-full px-[12px] md:px-[30px]">
        <v-row align="start">
            <v-col cols="12" md="12" lg="8" xl="9">
                <VideoPlayer :thumbnail="video.thumbnail"  :url_1440="video.url_1440" :url_1080="video.url_1080"  :url_720="video.url_720"   :url_480="video.url_480"  />

                <div class="">
                    <h2 class=" capitalize leading-normal">i took SNEAKO to a warzone [YouTube Cut]</h2>

                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-2 my-4">
                        <div class="flex items-center gap-4">
                            <img :src="ProfileImg" alt="Channel Image" class="size-[63px] rounded-full">
                            <div class="space-y-[2px]">
                                <h3 class="capitalize">{{ video.channel?.name }}</h3>
                                <p class="text-[16px] text-secondary">{{ video.created_at }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 md:gap-4 ml-auto">
                            <span class="min-w-fit max-w-[115px] md:max-w-[150px] flex items-center">
                                 <v-btn class="actionButtons" style="font-size: 16px ; border-radius: 30px 0 0 30px;" @click="toggleLike()"
                                        :color="has_liked ? 'var(--primary-dark-color)' : ''">
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

                    <p class="text-[16px]">{{ video.description }}</p>
                </div>

                <div class="mt-5 bg-comments">
                    <Comments :video="video"/>
                </div>
            </v-col>

            <v-col cols="12" md="12" lg="4" xl="3">
                <div class="w-full">
                    <div class="flex-col gap-5 mt-5 w-full hidden lg:flex">
                        <PlayerVideoCard v-for="(item, index) in videos" :key="index" :item="item" :random="true"
                            :user="video.user"
                           :orderNumber="index"
                            :customWidth="'w-full max-w-full'"
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
                                <div
                                    class="primaryCard min-h-[240px] p-[10px] flex flex-col gap-3 hover:opacity-85 cursor-pointer duration-300 max-w-[213px]">
                                    <div class=" w-full relative">
                        <span class="absolute right-2 bottom-2 bg-white/25 text-[11px] rounded px-[5px] py-[3px]">{{
                                item.duration
                            }}</span>
                                        <img :src="item.thumbnail" alt="placeholder Image" class="w-full rounded-[6px]">
                                    </div>
                                    <div class="w-full">
                                        <h3>{{ item.title }}</h3>
                                        <p class="text-sm text-secondary mb-4">{{ item.created_at }}</p>
                                        <p class="text-sm text-[#9F9F9F] flex items-center gap-1">
                                            {{ video.channel?.name }}
                                            <tick/>
                                        </p>
                                    </div>
                                </div>
                            </swiper-slide>
                        </swiper>
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
