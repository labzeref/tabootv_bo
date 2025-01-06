<script setup>
import Tick from "@/Components/svg/icons/tick.vue";
import Music from "@/Components/svg/icons/music.vue";

defineProps({
    video: Object,
})
</script>

<template>
    <div
        class="flex flex-col gap-1 md:gap-[10px] md:mb-6 pt-3 absolute md:relative z-10 bottom-2 left-1 max-w-[282px] md:max-w-[450px]">
        <div class="flex items-center gap-1">
            <img :src="video?.channel?.dp" alt="place holder"
                 class="size-8 rounded-full hidden md:inline">
            <span class="text-[14px] font-bold fm-bold">{{ video?.channel?.name }}</span>
            <Tick color="white"/>
            <span class="ml-1 text-xs text-secondary fm-book">{{ video?.humans_publish_at }}</span>
        </div>

        <p class="text-sm md:text-[16px] text-white break-words truncated-heading-2 max-title">{{ video?.title }}</p>

        <!-- <div class="md:flex items-center gap-[10px] hidden">
            <Music/>
            <p class="text-[16px] font-bold">video sound</p>
        </div> -->
    </div>
</template>
