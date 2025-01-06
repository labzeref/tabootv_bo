<script setup>
import demoVideo from '@/assets/video/demo-video.mp4'
import VideoPlayerBtn from "@/Components/svg/icons/videoPlayerBtn.vue";

</script>

<template>
    <div class="relative mb-5 h-full">
        <video class="w-full h-300 object-cover rounded-30">
            <source :src="demoVideo" type="video/mp4">
            <source src="movie.ogg" type="video/ogg">
            Your browser does not support the video tag.
        </video>
        <video-player-btn />
    </div>
</template>
