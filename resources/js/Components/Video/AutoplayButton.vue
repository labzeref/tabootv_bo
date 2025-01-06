<script setup>
import {onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import axios from "axios";

const user = usePage().props.auth.user;

const autoplay = ref(false)

const toggleAutoplay = () => {
    autoplay.value = !autoplay.value

    axios.post(route('videos.toggle-autoplay')).catch(error => {
        console.log('error', error)
    });
}

onMounted(() => {
    autoplay.value = user.video_autoplay
})
</script>

<template>

    <div @click="toggleAutoplay" class="max-w-[138px] max-h-7 md:max-h-9 rounded-[50px] p-autoplay text-[16px] leading-[20px] fm-book min-w-fit flex gap-[10px] items-center bg-white/10 hover:bg-white/15 transition-all duration-300 cursor-pointer">
        <div class="w-[26px] h-[14px] relative rounded-full overflow-hidden" :class="autoplay ? 'gradient-bg' : 'bg-dark'">
            <div class="size-[14px] bg-white/80 rounded-full absolute top-0 bottom-0" :class="autoplay ? 'right-0' : 'left-0'"></div>
        </div>

        <span class="autoplay fm-book">Autoplay</span>
    </div>
</template>

<style scoped>

.autoplay{
    font-size: 16px;
    font-weight: 400;
    line-height: 20px !important;
}

@media (max-width: 768px) {
    .autoplay{
        font-size: 13px;
        line-height: 12px;
    }
}

.gradient-bg{
    background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 142.56%);
}
</style>
