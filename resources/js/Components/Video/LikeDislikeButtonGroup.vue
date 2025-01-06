<script setup>
import {defineAsyncComponent, ref} from "vue";
import axios from "axios";

const FireSvg = defineAsyncComponent(() => import('@/Components/svg/icons/fire.vue'));
const TrashSvg = defineAsyncComponent(() => import('@/Components/svg/icons/trash.vue'));

const props = defineProps({
    video: Object,
})

const localVideo = ref(props.video);

const toggleLike = () => {
    axios.post(route('videos.toggle-like', localVideo.value.uuid)).then(response => {
        if (localVideo.value.has_disliked) {
            localVideo.value.has_disliked = false
            localVideo.value.dislikes_count--
        }
        if (localVideo.value.has_liked) {
            localVideo.value.has_liked = false;
            localVideo.value.likes_count--
        } else {
            localVideo.value.has_liked = true;
            localVideo.value.likes_count++
        }
    })
}
const toggleDislike = () => {
    axios.post(route('videos.toggle-dislike', localVideo.value.uuid)).then(response => {
        if (response.data) {
            if (localVideo.value.has_liked) {
                localVideo.value.has_liked = false
                localVideo.value.likes_count--
            }
            if (localVideo.value.has_disliked) {
                localVideo.value.has_disliked = false
                localVideo.value.dislikes_count--
            } else {
                localVideo.value.has_disliked = true
                localVideo.value.dislikes_count++
            }
        }
    });
}
</script>

<template>
    <div class="min-w-fit max-w-[115px] md:max-w-[150px] flex items-center fm-bold">
        <v-btn class="actionButtons" style="font-size: 16px ; border-radius: 30px 0 0 30px; font-weight: 400; line-height: 20px !important"
               @click="toggleLike()"
               :color="localVideo.has_liked ? 'var(--primary-dark-color)' : ''">
            <FireSvg class="size-4 md:size-5 ml-0" />
            {{ localVideo.likes_count }}
        </v-btn>
        <v-btn class="actionButtons" @click="toggleDislike()"
               :color="localVideo.has_disliked ? 'var(--primary-dark-color)' : ''"
               style="font-size: 16px ; border-radius: 0 30px 30px 0; border-left: 2px solid var(--secondary-color); font-weight: 400; line-height: 20px !important">
            <TrashSvg class="size-4 md:size-5" />
            {{ localVideo.dislikes_count }}
        </v-btn>
    </div>
</template>

<style scoped>
.actionButtons {
    height: 30px !important;
}

@media (max-width: 768px) {
    .actionButtons {
        height: 28px !important;
    }
}

</style>
