<script setup>
import {ref, watch, onMounted, onBeforeUnmount} from "vue";
import MuteSpeakerSvg from "@/Components/svg/icons/muteSpeaker.vue";
import UnMuteSpeakerSvg from "@/Components/svg/icons/unMuteSpeaker.vue";
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import shortVideosStore from "@/stores/shortVideosStore.js";
import demoVideo from "@/assets/video/short.mp4"


const props = defineProps({
    url: String,
    index: Boolean,
})

const videoPlayerRef = ref(null);
const isMuted = ref(true);

const toggleMute = () => {
    isMuted.value = !isMuted.value
    videoPlayerRef.value.muted = !videoPlayerRef.value.muted
}

watch(() => shortVideosStore.displayVideoIndex, (newVal) => {
    if (newVal === props.index) {
        videoPlayerRef.value.play();
    } else {
        videoPlayerRef.value.pause();
    }
})


// ----------------------------Video Height----------------
const viewportHeight = ref(666)

const updateViewportHeight = () => {
    if (window.innerHeight > 740) {
        viewportHeight.value = window.innerHeight - 96
    }
}


onMounted(() => {
    window.addEventListener('resize', updateViewportHeight)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateViewportHeight)
})
</script>

<template>
    <div class="relative overflow-hidden group">
        <div
            class="absolute top-0 bg-gradient-to-t from-transparent to-gray-300/50 p-2 left-0 right-0 rounded-t-xl flex items-center gap-2 z-10 invisible group-hover:visible transition-all duration-200">
            <div @click="toggleMute">
                <v-btn v-if="isMuted" :icon="MuteSpeakerSvg" color="transparent" style="box-shadow: none"/>
                <v-btn v-else :icon="UnMuteSpeakerSvg" color="transparent" style="box-shadow: none"/>
            </div>
            <!-- <v-btn :icon="true" color="transparent" style="box-shadow: none">
                <three-dots color="white"/>
            </v-btn> -->
        </div>
        <video
            ref="videoPlayerRef"
            class="bg-black height-video-short"
            preload="metadata"
            muted
            loop
            autoplay
            playsinline

        >
            <source :src="url" preload="metadata" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</template>

