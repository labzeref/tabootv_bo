<script setup>
import demoVideo from "@/assets/video/demo-video.mp4";
import {defineAsyncComponent, ref} from "vue";

const Spinner = defineAsyncComponent(() => import('@/Components/Spinner.vue'))

defineProps({
    thumbnail: String,
    video: String,
})

const showThumbnail = ref(true)

const playerLoaded = () => {
    showThumbnail.value = false;
}
</script>

<template>
    <div class="relative rumble__player__container">
        <img v-if="showThumbnail" class="absolute h-full w-full" :src="thumbnail" alt="">
        <div v-if="showThumbnail" class="absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%]">
            <Spinner/>
        </div>
        <div class="">
            <iframe class="rumble__iframe" :src="video" @load="playerLoaded" allowfullscreen></iframe>
        </div>
    </div>
</template>

<style scoped>
.rumble__player__container {
    width: 100%;
    border-radius: 30px;
    overflow: hidden;
}

.rumble__iframe {
    width: 100%;
    height: 100%;
    border: none;
    aspect-ratio: 16 / 9;
}
</style>
